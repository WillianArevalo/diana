<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
use App\Models\User;
use App\Models\Workplace;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function create()
    {
        $user = auth()->user();
        $workplace = Workplace::find($user->workplace_id);

        return view("facilitadores.horarios.create", compact("workplace"));
    }

    public function store(Request $request)
    {

        DB::beginTransaction();
        try {
            $workplace = Workplace::find(auth()->user()->workplace_id);
            $schedule = Schedule::create($request->all());

            if ($request->type === "day") {
                $workplace->daytimeSchedule()->associate($schedule);
            } else {
                $workplace->nighttimeSchedule()->associate($schedule);
            }

            $workplace->save();

            DB::commit();
            return redirect()->route("facilitadores.dashboard")->with("success", "Horario creado correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route("horarios.create")->with("error", "Error al crear el horario. " . $e->getMessage());
        }
    }

    public function assign(Request $request)
    {
        DB::beginTransaction();
        try {
            $userIds = explode(",", $request->user_ids);
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                $user->schedule_id = $request->schedule_id;
                $user->save();
            }
            DB::commit();
            return redirect()->route("facilitadores.dashboard")->with("success", "Horario asignado correctamente");
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route("facilitadores.dashboard")->with("error", "Error al asignar el horario. " . $e->getMessage());
        }
    }
}
