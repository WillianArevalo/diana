<?php

namespace App\Http\Controllers;

use App\Models\Marking;
use App\Models\User;
use Aws\Rekognition\RekognitionClient;
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
        return view("rrhh.colaborator.show", compact("user"));
    }
}
