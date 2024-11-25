<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Workplace;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class RRHHController extends Controller
{
    public function index()
    {
        $users = User::where("role", "collaborator")->get();
        $workplaces = Workplace::all();
        return view("rrhh.dashboard", compact("users", "workplaces"));
    }

    public function asistencias()
    {
        $users = User::where("role", "collaborator")->get();

        foreach ($users as $user) {
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

            if ($user->permissions) {
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
            }

            $marks = $user->marks->map(function ($mark) {
                $mark->type_marking = 'mark';
                return $mark;
            });

            $permissions = $user->permissions->map(function ($permission) {
                $permission->type_marking = 'permission';
                return $permission;
            });

            $user->marks = $marks->merge($permissions);
        }


        return view("rrhh.asistencias", compact("users"));
    }

    public function getAsistencias()
    {
        $users = User::where("role", "collaborator")->with("seventh")->whereHas("marks")->get();

        foreach ($users as $user) {

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

                //Normales
                $HRD = 0;
                $HED = 0;
                $HRN = 0;
                $HEN = 0;

                //Septimo
                $HRDS = 0;
                $HRNS = 0;
                $HEDS = 0;
                $HENS = 0;

                //Feriados
                $HRDF = 0;
                $HRNF = 0;
                $HEDF = 0;
                $HENF = 0;

                $mark->isSeventh = false;
                $mark->isHoliday = false;


                if ($user->seventh) {
                    if (strtolower(
                        \Carbon\Carbon::parse($mark->date)->locale('es')->translatedFormat('l')
                    ) === strtolower($user->seventh->day)) {
                        $mark->isSeventh = true;
                    }
                }


                foreach ($user->marks as $mark) {
                    $mark->isHoliday = $holidays->some(function ($holiday) use ($mark) {
                        $markDate = \Carbon\Carbon::parse($mark->date);
                        $holidayStart = \Carbon\Carbon::parse($holiday->date_start);
                        $holidayEnd = \Carbon\Carbon::parse($holiday->date_end);

                        return $markDate->between($holidayStart, $holidayEnd);
                    });
                }

                $hours_schedule = 0;

                if ($type_schedule == "day") {
                    if ($mark->entry_time < $time_start_day) {
                        $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                        $time_start = new DateTime($time_start_day, new DateTimeZone('America/El_Salvador'));
                        if ($mark->isSeventh) {
                            $HEDS += $time_start->diff($entry)->h;
                        } else if ($mark->isHoliday) {
                            $HEDF += $time_start->diff($entry)->h;
                        } else {
                            $HED += $time_start->diff($entry)->h;
                        }
                    }

                    if ($mark->entry_time && $mark->lunch_time_start) {
                        $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                        $lunch_start = new DateTime($mark->lunch_time_start, new DateTimeZone('America/El_Salvador'));

                        if ($mark->isSeventh) {
                            $HRDS += $lunch_start->diff($entry)->h;
                        } elseif ($mark->isHoliday) {
                            $HEDF += $lunch_start->diff($entry)->h;
                        } else {
                            $HRD += $lunch_start->diff($entry)->h;
                        }
                    }

                    if ($mark->exit_time && $mark->lunch_time_end) {
                        $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                        $lunch_end = new DateTime($mark->lunch_time_end, new DateTimeZone('America/El_Salvador'));

                        if ($mark->isSeventh) {
                            $HRDS += $lunch_end->diff($exit)->h;
                        } elseif ($mark->isHoliday) {
                            $HEDF += $lunch_end->diff($exit)->h;
                        } else {
                            $HRD += $lunch_end->diff($exit)->h;
                        }
                    }

                    if ($mark->exit_time > $time_end_day) {
                        $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                        $time_end = new DateTime($time_end_day, new DateTimeZone('America/El_Salvador'));

                        if ($mark->isSeventh) {
                            $HEDS += $time_end->diff($exit)->h;
                            $HRDS -= $time_end->diff($exit)->h;
                        } elseif ($mark->isHoliday) {
                            $HEDF += $time_end->diff($exit)->h;
                            $HRDF -= $time_end->diff($exit)->h;
                        } else {
                            $HED += $time_end->diff($exit)->h;
                            $HRD -= $time_end->diff($exit)->h;
                        }
                    }

                    if ($mark->exit_time > $time_start_night) {
                        $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                        $time_start = new DateTime($time_start_night, new DateTimeZone('America/El_Salvador'));

                        if ($mark->isSeventh) {
                            $HRNS += $time_start->diff($exit)->h;
                            $HRDS -= $time_start->diff($exit)->h;
                        } elseif ($mark->isHoliday) {
                            $HRNF -= $time_start->diff($exit)->h;
                            $HEDF -= $time_start->diff($exit)->h;
                        } else {
                            $HRN += $time_start->diff($exit)->h;
                            $HED -= $time_start->diff($exit)->h;
                        }
                    }
                } else {

                    if ($mark->entry_time && $mark->lunch_time_start) {
                        $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                        $lunch_start = new DateTime($mark->lunch_time_start, new DateTimeZone('America/El_Salvador'));
                        $HRN += $lunch_start->diff($entry)->h;

                        if ($mark->isSeventh) {
                            $HRNS += $lunch_start->diff($entry)->h;
                        } else  if ($mark->isHoliday) {
                            $HRNF += $lunch_start->diff($entry)->h;
                        } else {
                            $HRN += $lunch_start->diff($entry)->h;
                        }
                    }

                    if ($mark->exit_time && $mark->lunch_time_end) {
                        $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                        $lunch_end = new DateTime($mark->lunch_time_end, new DateTimeZone('America/El_Salvador'));
                        if ($exit < $lunch_end) {
                            $exit = $exit->modify('+1 day');
                        }
                        $HRN += $lunch_end->diff($exit)->h;

                        if ($mark->isSeventh) {
                            $HRNS += $lunch_end->diff($exit)->h;
                        } else  if ($mark->isHoliday) {
                            $HRNF += $lunch_end->diff($exit)->h;
                        } else {
                            $HRN += $lunch_end->diff($exit)->h;
                        }
                    }

                    if ($mark->exit_time > $time_end_night) {
                        $exit = new DateTime($mark->exit_time, new DateTimeZone('America/El_Salvador'));
                        $time_end = new DateTime($time_end_night, new DateTimeZone('America/El_Salvador'));

                        if ($mark->isSeventh) {
                            $HENS += $time_end->diff($exit)->h;
                            $HRNS -= $time_end->diff($exit)->h;
                        } else if ($mark->isHoliday) {
                            $HENF += $time_end->diff($exit)->h;
                            $HRNF -= $time_end->diff($exit)->h;
                        } else {
                            $HEN += $time_end->diff($exit)->h;
                            $HRN -= $time_end->diff($exit)->h;
                        }
                    }

                    if ($mark->entry_time < $time_end_day) {
                        $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                        $time_end = new DateTime($time_end_day, new DateTimeZone('America/El_Salvador'));

                        if ($mark->isSeventh) {
                            $HRDS += $time_end->diff($entry)->h;
                        } else  if ($mark->isHoliday) {
                            $HRDF += $time_end->diff($entry)->h;
                        } else {
                            $HRD += $time_end->diff($entry)->h;
                        }
                    }

                    if ($mark->entry_time < $time_start_night) {
                        if ($mark->entry_time > $time_end_day) {
                            $entry = new DateTime($mark->entry_time, new DateTimeZone('America/El_Salvador'));
                            $time_start = new DateTime($time_start_night, new DateTimeZone('America/El_Salvador'));

                            if ($mark->isSeventh) {
                                $HEDS += $time_start->diff($entry)->h;
                                $HRNS -= $time_start->diff($entry)->h;
                            } else if ($mark->isHoliday) {
                                $HEDF += $time_start->diff($entry)->h;
                                $HRNF -= $time_start->diff($entry)->h;
                            } else {
                                $HED += $time_start->diff($entry)->h;
                                $HRN -= $time_start->diff($entry)->h;
                            }
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

                $mark->HRDS = $HRDS;
                $mark->HEDS = $HEDS;
                $mark->HRNS = $HRNS;
                $mark->HENS = $HENS;

                $mark->HRDF = $HRDF;
                $mark->HEDF = $HEDF;
                $mark->HRNF = $HRNF;
                $mark->HENF = $HENF;
            }

            if ($user->permissions) {
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
            }

            $marks = $user->marks->map(function ($mark) {
                $mark->type_marking = 'mark';
                return $mark;
            });

            $permissions = $user->permissions->map(function ($permission) {
                $permission->type_marking = 'permission';
                return $permission;
            });

            $user->marks = $marks->merge($permissions);
        }

        return $users;
    }

    public function generateExcel()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // 1. Encabezado del Reporte
        $sheet->setCellValue('A1', 'Reporte de Ventas');
        $sheet->mergeCells('A1:Y1');
        $sheet->getStyle('A1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 14,
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ],
        ]);

        // 2. Encabezados de columnas
        $sheet->setCellValue('A2', 'Código empleado');
        $sheet->setCellValue('B2', 'Nombre');
        $sheet->setCellValue('C2', 'Día');
        $sheet->setCellValue('D2', 'Fecha');
        $sheet->setCellValue('E2', 'Horario');
        $sheet->setCellValue('F2', 'Entrada');
        $sheet->setCellValue('G2', 'R.Salida');
        $sheet->setCellValue('H2', 'R.Entrada');
        $sheet->setCellValue('I2', 'Salida');
        $sheet->setCellValue('J2', 'Tipo');
        $sheet->setCellValue('K2', 'SAB');

        $sheet->setCellValue('L2', 'HRD');
        $sheet->setCellValue('M2', 'HRN');
        $sheet->setCellValue('N2', 'HEN');
        $sheet->setCellValue('O2', 'HED');

        $sheet->setCellValue('P2', 'H.SEP');
        $sheet->setCellValue('Q2', 'H.COM');
        $sheet->setCellValue('R2', 'HRD');
        $sheet->setCellValue('S2', 'HRN');
        $sheet->setCellValue('T2', 'HED');
        $sheet->setCellValue('U2', 'HEN');

        $sheet->setCellValue('V2', 'HFE');
        $sheet->setCellValue('W2', 'HRD');
        $sheet->setCellValue('X2', 'HRN');
        $sheet->setCellValue('Y2', 'HED');
        $sheet->setCellValue('Z2', 'HEN');
        $sheet->setCellValue('AA2', 'OBSERVACIONES');

        // Estilo para encabezados
        $sheet->getStyle('A2:N2')->applyFromArray([
            'font' => [
                'bold' => true,
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => [
                    'argb' => 'FFFFE0B2',
                ],
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // 3. Rellenar datos dinámicamente
        $data = $this->getAsistencias();
        $row = 3;

        foreach ($data as $user) {
            if ($user->marks) {

                foreach ($user->marks as $mark) {
                    $sheet->setCellValue('A' . $row, $user->cod_user);
                    $sheet->setCellValue('B' . $row, $user->username);
                    $sheet->setCellValue('C' . $row, strtolower(Carbon::parse($mark->date)->locale('es')->shortDayName));
                    $sheet->setCellValue('D' . $row, Carbon::parse($mark->date)->format('d/m/Y'));
                    $sheet->setCellValue('E' . $row, $user->schedule->type);
                    $sheet->setCellValue('F' . $row, $mark->entry_time);
                    $sheet->setCellValue('G' . $row, $mark->exit_time);
                    $sheet->setCellValue('H' . $row, $mark->lunch_time_start);
                    $sheet->setCellValue('I' . $row, $mark->lunch_time_end);
                    $sheet->setCellValue('J' . $row, $mark->type);
                    $sheet->setCellValue('K' . $row, $mark->is_holiday);

                    $sheet->setCellValue('L' . $row, $mark->HRD);
                    $sheet->setCellValue('M' . $row, $mark->HRN);
                    $sheet->setCellValue('N' . $row, $mark->HEN);
                    $sheet->setCellValue('O' . $row, $mark->HED);

                    $sheet->setCellValue('P' . $row, $mark->HRDS);
                    $sheet->setCellValue('Q' . $row, "");
                    $sheet->setCellValue('R' . $row, $mark->HRDS);
                    $sheet->setCellValue('S' . $row, $mark->HRNS);
                    $sheet->setCellValue('T' . $row, $mark->HEDS);
                    $sheet->setCellValue('U' . $row, $mark->HENS);


                    $sheet->setCellValue('V' . $row, $mark->HRDF);
                    $sheet->setCellValue('W' . $row, $mark->HRNF);
                    $sheet->setCellValue('X' . $row, $mark->HRNF);
                    $sheet->setCellValue('Y' . $row, $mark->HEDF);
                    $sheet->setCellValue('Z' . $row, $mark->HENF);
                    $sheet->setCellValue('AA' . $row, "Sin observaciones");
                    $row++;
                }
            }
        }

        // 4. Estilo para los datos
        $sheet->getStyle('A3:D' . ($row - 1))->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                ],
            ],
        ]);

        // 5. Descargar el archivo
        $writer = new Xlsx($spreadsheet);
        $filename = 'Reporte_Ventas.xlsx';

        // Cabeceras para descarga
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');

        $writer->save('php://output');
        exit;
    }
}