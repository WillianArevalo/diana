<?php

namespace App\Http\Controllers;

use App\Models\Marking;
use App\Models\User;
use Aws\Rekognition\RekognitionClient;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CollaboratorsController extends Controller
{
    public function index()
    {
        $currentDate = Carbon::now()->timezone("America/El_Salvador")->toDateString();
        $currentTime = Carbon::now();

        $markings = Marking::where("user_id", auth()->user()->id)
            ->whereDate("date", $currentDate)
            ->first();

        $hasAllMarks = $markings &&
            $markings->entry_time &&
            $markings->exit_time &&
            $markings->lunch_time_start &&
            $markings->lunch_time_end;

        // Coordenadas de la empresa desde la configuración
        $lat_empresa = config("app.lat_empresa");
        $lng_empresa = config("app.lng_empresa");

        return view("colaboradores.dashboard", compact(
            "markings",
            "hasAllMarks",
            "currentTime",
            "lat_empresa",
            "lng_empresa"
        ));
    }


    public function registerMarkingError()
    {
        return view("error.register-marking");
    }

    public function registerMarking(Request $request)
    {
        DB::beginTransaction();
        try {

            $date = Carbon::now()->timezone("America/El_Salvador")->format("Y-m-d");
            $time = Carbon::now()->timezone("America/El_Salvador")->format("H:i:s");
            $marking = Marking::where("date", $date)->where("user_id", auth()->user()->id)->first();

            if (!$marking) {
                $marking = Marking::create([
                    "date" => $date,
                    "user_id" => auth()->user()->id,
                ]);
            }

            switch ($request->type_marking) {
                case "start":
                    $marking->entry_time = $time;
                    $marking->type = "labor";
                    $marking->save();
                    break;

                case "lunch_start":
                    $marking->lunch_time_start = $time;
                    $marking->type = "labor";
                    $marking->save();
                    break;

                case "lunch_end":
                    $marking->lunch_time_end = $time;
                    $marking->type = "labor";
                    $marking->save();
                    break;

                case "end":
                    $marking->exit_time = $time;
                    $marking->type = "labor";
                    $marking->save();
                    break;

                default:
                    return response()->json([
                        "error" => "Tipo de marca no válido.",
                    ], 400);
            }

            if ($request->hasFile("photo")) {
                $pathName = auth()->user()->cod_user;
                $file = $request->file("photo");
                $folderPath = "markings/{$pathName}/{$date}";
                Storage::disk("public")->makeDirectory($folderPath);
                $fileName = $date . "_" . $request->type_marking . "." . $file->getClientOriginalExtension();
                $filePath = $file->storeAs($folderPath, $fileName, "public");
                $marking->photo = $filePath;
                $marking->save();
            }

            DB::commit();

            $image1Path = storage_path('app/public/' . auth()->user()->photo);
            $image2Path = storage_path('app/public/' . $marking->photo);

            $image1 = file_get_contents($image1Path);
            $image2 = file_get_contents($image2Path);

            $rekognitionClient = new RekognitionClient([
                'version' => 'latest',
                'region' => env('AWS_DEFAULT_REGION'),
                'credentials' => [
                    'key'    => env('AWS_ACCESS_KEY_ID'),
                    'secret' => env('AWS_SECRET_ACCESS_KEY'),
                ]
            ]);

            try {
                $result = $rekognitionClient->compareFaces([
                    'SourceImage' => [
                        'Bytes' => $image1,
                    ],
                    'TargetImage' => [
                        'Bytes' => $image2,
                    ],
                    'SimilarityThreshold' => 80,
                ]);

                if (count($result['FaceMatches']) > 0) {
                    return redirect()->route("colaboradores.dashboard")->with("success", "Marca registrada correctamente");
                } else {

                    switch ($request->type_marking) {
                        case "start":
                            $marking->delete();
                            Storage::disk("public")->delete($marking->photo);
                            break;

                        case "lunch_start":
                            $marking->lunch_time_start = null;
                            $marking->photo = null;
                            $marking->save();
                            break;

                        case "lunch_end":
                            $marking->lunch_time_end = null;
                            $marking->photo = null;
                            $marking->save();
                            break;

                        case "end":
                            $marking->exit_time = null;
                            $marking->photo = null;
                            $marking->save();
                            break;
                    }

                    return redirect()->route("colaboradores.dashboard")->with("error", "Por favor, asegurate de ser un usuario autorizado. Marca no registrada.");
                }
            } catch (\Aws\Exception\AwsException $e) {
                return response()->json(['error' => 'Error en Rekognition: ' . $e->getMessage()], 500);
            }
        } catch (\Exception $e) {
            DB::rollBack();
            dd($e->getMessage());
            return redirect()->route("colaboradores.dashboard")->with("error", "Error al registrar la marca. Error: " . $e->getMessage());
        }
    }

    function show(string $id)
    {
        $user = User::with("marks")->find($id);

        //Calculo de horas trabajadas
        $difference_1 = 0;
        $difference_2 = 0;

        $workplace = $user->workplace;
        $holidays = $workplace->holidays;

        foreach ($user->marks as $mark) {

            //Schedule user
            $schedule = $user->schedule;

            //Schedule workplace
            $workplace = $schedule->workplace;
            $schedule_day = $workplace->daytimeSchedule;
            $schedule_night = $workplace->nighttimeSchedule;
            $type_schedule = $schedule->type;

            //Time start and end schedule
            $time_start_day = $schedule_day->time_start;
            $time_end_day = $schedule_day->time_end;
            $time_start_night = $schedule_night->time_start;
            $time_end_night = $schedule_night->time_end;

            $HRD = 0;
            $HED = 0;
            $HRN = 0;
            $HEN = 0;

            $hours_schedule = 0;

            if ($type_schedule == "day") {
                if ($mark->entry_time < $time_start_day) {
                    $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                    $time_start = new DateTime($time_start_day, new DateTimeZone('America/El_Salvador'));
                    $HED += $time_start->diff($entry)->h;
                }

                if ($mark->entry_time && $mark->lunch_time_start) {
                    $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                    $lunch_start = new DateTime($mark->lunch_time_start, new DateTimeZone('America/El_Salvador'));
                    $HRD += $lunch_start->diff($entry)->h;
                }

                if ($mark->exit_time && $mark->lunch_time_end) {
                    $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                    $lunch_end = new DateTime($mark->lunch_time_end, new DateTimeZone('America/El_Salvador'));
                    $HRD += $lunch_end->diff($exit)->h;
                }

                if ($mark->exit_time > $time_end_day) {
                    $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                    $time_end = new DateTime($time_end_day, new DateTimeZone('America/El_Salvador'));
                    $HED += $time_end->diff($exit)->h;
                    $HRD -= $time_end->diff($exit)->h;
                }

                if ($mark->exit_time > $time_start_night) {
                    $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                    $time_start = new DateTime($time_start_night, new DateTimeZone('America/El_Salvador'));
                    $HRN += $time_start->diff($exit)->h;
                    $HED -= $time_start->diff($exit)->h;
                }
            } else {

                if ($mark->entry_time && $mark->lunch_time_start) {
                    $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                    $lunch_start = new DateTime($mark->lunch_time_start, new DateTimeZone('America/El_Salvador'));
                    $HRN += $lunch_start->diff($entry)->h;
                }

                if ($mark->exit_time && $mark->lunch_time_end) {
                    $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                    $lunch_end = new DateTime($mark->lunch_time_end, new DateTimeZone('America/El_Salvador'));
                    if ($exit < $lunch_end) {
                        $exit = $exit->modify('+1 day');
                    }
                    $HRN += $lunch_end->diff($exit)->h;
                }

                if ($mark->exit_time > $time_end_night) {
                    $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                    $time_end = new DateTime($time_end_night, new DateTimeZone('America/El_Salvador'));
                    $HEN += $time_end->diff($exit)->h;
                    $HRN -= $time_end->diff($exit)->h;
                }

                if ($mark->entry_time < $time_end_day) {
                    $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                    $time_end = new DateTime($time_end_day, new DateTimeZone('America/El_Salvador'));
                    $HRD += $time_end->diff($entry)->h;
                    $HRN -= $time_end->diff($entry)->h;
                }

                if ($mark->entry_time < $time_start_night) {
                    if ($mark->entry_time > $time_end_day) {
                        $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                        $time_start = new DateTime($time_start_night, new DateTimeZone('America/El_Salvador'));
                        $HED += $time_start->diff($entry)->h;
                        $HRN -= $time_start->diff($entry)->h;
                    } else {
                        $entry = new DateTime($time_end_day, new DateTimeZone('America/El_Salvador'));
                        $time_start = new DateTime($time_start_night, new DateTimeZone('America/El_Salvador'));
                        $HED += $time_start->diff($entry)->h;
                        $HRN -= $time_start->diff($entry)->h;
                    }
                }
            }

            $mark->HRD = $HRD;
            $mark->HED = $HED;
            $mark->HRN = $HRN;
            $mark->HEN = $HEN;
        }

        foreach ($user->permissions as $permission) {
            if ($permission->type === "Permiso con" || $permission->type === "Incapacidad" || $permission->type === "Otros") {
                $schedule = $permission->user->schedule;
                //Schedule workplace
                $time_start = $schedule->time_start->format("H:i:s");
                $time_end = $schedule->time_end->format("H:i:s");

                $lunch_start = $schedule->break_start->format("H:i:s");
                $lunch_end = $schedule->break_end->format("H:i:s");

                $permission_start = $permission->date_start->format("H:i:s");
                $permission_end = $permission->date_end->format("H:i:s");

                if ($schedule->type == "day") {
                    if ($permission_start == $time_start && $permission_end == $time_end) {
                        $permission->HRD = $schedule->hours_day;
                    } else {
                        $entry = new DateTime($permission->date_start, new DateTimeZone('America/El_Salvador'));
                        $time_start = new DateTime($time_start, new DateTimeZone('America/El_Salvador'));
                        $permission->HRD = $time_start->diff($entry)->h;
                    }
                } else {
                    if ($permission_start == $time_start && $permission_end == $time_end) {
                        $permission->HRN = $schedule->hours_night;
                    } else {
                        $entry = new DateTime($permission_start, new DateTimeZone('America/El_Salvador'));
                        $end = new DateTime($permission_end, new DateTimeZone('America/El_Salvador'));
                        if ($end < $entry) {
                            $end = $end->modify('+1 day');
                        }
                        $permission->HRN = $end->diff($entry)->h;
                    }
                }
            }
        }

        $marks = $user->marks->map(function ($mark) {
            $mark->type_marking = 'mark';
            return $mark;
        });

        $permissions = $user->permissions->map(function ($permission) {
            $permission->type_marking = 'permission';
            return $permission;
        });

        foreach ($user->marks as $mark) {
            $mark->is_holiday = $holidays->some(function ($holiday) use ($mark) {
                $markDate = \Carbon\Carbon::parse($mark->date);
                $holidayStart = \Carbon\Carbon::parse($holiday->date_start);
                $holidayEnd = \Carbon\Carbon::parse($holiday->date_end);

                return $markDate->between($holidayStart, $holidayEnd);
            });
        }

        $user->marks = $marks->merge($permissions);
        return view("rrhh.colaborator.show", compact("user", "holidays"));
    }
}