<?php

namespace App\Http\Controllers;

use App\Models\Marking;
use App\Models\Schedule;
use App\Models\Seventh;
use App\Models\User;
use App\Models\Workplace;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class FacilitatorsController extends Controller
{
    public function index()
    {
        $users = User::where("workplace_id", auth()->user()->workplace_id)
            ->where("role", "collaborator")
            ->with("marks")
            ->get();

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
                    "date_seventh" => $request->date_seventh
                ]);
            }
            DB::commit();
            return redirect()->route("facilitadores.dashboard")->with("success", "Septimo asignado correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route("facilitadores.dashboard")->with("error", "Error al asignar el septimo. " . $e->getMessage());
        }
    }
}
