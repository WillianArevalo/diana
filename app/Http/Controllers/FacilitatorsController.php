<?php

namespace App\Http\Controllers;

use App\Models\Holiday;
use App\Models\Marking;
use App\Models\Permission;
use App\Models\Schedule;
use App\Models\Seventh;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class FacilitatorsController extends Controller
{
    public function index()
    {
        $users = User::where("workplace_id", auth()->user()->workplace_id)
            ->where("role", "collaborator")
            ->with("marks")
            ->get();

        $date = now()->setTimezone('America/El_Salvador');
        $yesterday = $date->copy()->subDay();

        foreach ($users as $user) {
            $user->marksYesterday = $user->marks->filter(
                fn($mark) => $mark->date->format('Y-m-d') === $yesterday->format('Y-m-d'),
            );

            $user->countMarksYesterday = 0;

            foreach ($user->marksYesterday as $mark) {
                if ($mark->entry_time) {
                    $user->countMarksYesterday++;
                }
                if ($mark->exit_time) {
                    $user->countMarksYesterday++;
                }
                if ($mark->lunch_time_start) {
                    $user->countMarksYesterday++;
                }
                if ($mark->lunch_time_end) {
                    $user->countMarksYesterday++;
                }
            }

            $user->hasPermission = $user->permissions->first(function ($permission) use ($yesterday, $date) {
                $permissionStart = \Carbon\Carbon::parse($permission->date_start);
                $permissionEnd = \Carbon\Carbon::parse($permission->date_end);
                $coversYesterday = $yesterday->between($permissionStart->startOfDay(), $permissionEnd->endOfDay());
                $validToday = $date->between($permissionStart, $permissionEnd);
                return $coversYesterday || $validToday;
            });

            $lastPermission = $user->permissions->sortByDesc('date_end')->first() ?? null;
            $currentDate = null;
            $lastPermissionDate = null;
            if ($lastPermission) {
                $currentDate = now()->timezone('America/El_Salvador')->format('Y-m-d h:i:s A');
                $lastPermissionDate = $lastPermission->date_end->format('Y-m-d h:i:s A');
            }
            $user->last_permission = $lastPermission;
            $user->last_permission_date = $lastPermissionDate;
            $user->current_date = $currentDate;
        }

        $workplace = Workplace::find(auth()->user()->workplace_id);

        $daytimeSchedule = $workplace->daytimeSchedule;
        $nighttimeSchedule = $workplace->nighttimeSchedule;

        $schedules = [];

        if ($daytimeSchedule) {
            $schedules[] = $daytimeSchedule;
        }

        if ($nighttimeSchedule) {
            $schedules[] = $nighttimeSchedule;
        }

        return view("facilitadores.dashboard", compact(
            "users",
            "workplace",
            "schedules",
            "daytimeSchedule",
            "nighttimeSchedule"
        ));
    }

    public function assignSeventh(Request $request)
    {
        DB::beginTransaction();
        try {
            $userIds = explode(",", $request->user_ids);
            foreach ($userIds as $userId) {
                Seventh::create([
                    "user_id" => $userId,
                    "day" => $request->day
                ]);
            }
            DB::commit();
            return redirect()->route("facilitadores.dashboard")->with("success", "Septimo asignado correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route("facilitadores.dashboard")->with("error", "Error al asignar el septimo. " . $e->getMessage());
        }
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            "user_id" => "required|exists:users,id",
            "type" => "required|string",
            "date_start" => "required|date",
            "date_end" => "required|date",
            "description" => "nullable|string",
            "document" => "nullable|file"
        ]);

        DB::beginTransaction();
        try {

            if ($request->hasFile("document")) {
                $pathName = auth()->user()->cod_user;
                $file = $request->file("document");
                $folderPath = "permissions/{$pathName}";
                Storage::disk("public")->makeDirectory($folderPath);
                $fileName = $file->getClientOriginalName();
                $filePath = $file->storeAs($folderPath, $fileName, "public");
                $request->merge(["file" => $filePath]);
            }
            Permission::create($request->all());
            DB::commit();
            return redirect()->route("facilitadores.dashboard")->with("success", "Permiso creado correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route("facilitadores.dashboard")->with("error", "Error al crear el permiso. " . $e->getMessage());
        }
    }

    public function createHoliday()
    {
        $holidays = Holiday::where("workplace_id", auth()->user()->workplace_id)->get();

        $holidays = $holidays->map(function ($holiday) {
            return [
                "id" => $holiday->id,
                "title" => $holiday->name,
                "start" => $holiday->date_start->format('Y-m-d'),
                "end" => $holiday->date_end->format('Y-m-d'),
            ];
        });

        return view("facilitadores.asuetos.create", compact("holidays"));
    }

    public function storeHoliday(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "date_start" => "required|date",
            "date_end" => "required|date",
        ]);

        DB::beginTransaction();
        try {
            $request->merge(["workplace_id" => auth()->user()->workplace_id]);
            Holiday::create($request->all());
            DB::commit();
            return redirect()->route("asuetos.create")->with("success", "Asueto creado correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route("asuetos.create")->with("error", "Error al crear el asueto.");
        }
    }
}