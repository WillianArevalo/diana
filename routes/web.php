<?php

use App\Http\Controllers\CollaboratorsController;
use App\Http\Controllers\FacilitatorsController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\RRHHController;
use App\Http\Controllers\ScheduleController;
use Illuminate\Support\Facades\Route;


Route::get("/", [LoginController::class, "index"])->name("login");
Route::post("/login", [LoginController::class, "validate"])->name("login.validate");
Route::post("/logout", [LoginController::class, "logout"])->name("logout");


Route::middleware(["role:facilitator", "auth"])->group(function () {
    Route::get("/facilitadores", [FacilitatorsController::class, "index"])->name("facilitadores.dashboard");
    Route::post("/septimo/asignar", [FacilitatorsController::class, "assignSeventh"])->name("septimo.assign");
    Route::post("/observaciones/crear", [FacilitatorsController::class, "storePermission"])->name("observaciones.store");
    Route::get("/asuetos/crear", [FacilitatorsController::class, "createHoliday"])->name("asuetos.create");
    Route::post("/asuetos/crear", [FacilitatorsController::class, "storeHoliday"])->name("asuetos.store");

    Route::get("/horarios/crear", [ScheduleController::class, "create"])->name("horarios.create");
    Route::post("/horarios/crear", [ScheduleController::class, "store"])->name("horarios.store");
    Route::post("/horarios/asignar", [ScheduleController::class, "assign"])->name("horarios.assign");
});

Route::middleware(["role:collaborator", "auth"])->group(function () {
    Route::get("/colaboradores", [CollaboratorsController::class, "index"])->name("colaboradores.dashboard");
    Route::get("/error/register-marking", [CollaboratorsController::class, "registerMarkingError"])->name("error.register-marking");
    Route::post("/register-marking", [CollaboratorsController::class, "registerMarking"])->name("register.marking");
});



Route::middleware(["role:rrhh", "auth"])->group(function () {
    Route::get("/rrhh", [RRHHController::class, "index"])->name("rrhh.dashboard");
    Route::get("/rrhh/asistencias", [RRHHController::class, "asistencias"])->name("rrhh.asistencias");
    Route::get("/rrhh/asistencias/excel", [RRHHController::class, "generateExcel"])->name("rrhh.asistencias.excel");
    Route::get("/asistencias", [RRHHController::class, "getAsistencias"])->name("rrhh.asistencias.get");
    Route::get("/colaboradores/{id}", [CollaboratorsController::class, "show"])->name("colaboradores.show");
});