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

Route::get("/facilitadores", [FacilitatorsController::class, "index"])->name("facilitadores.dashboard");
Route::post("/septimo/asignar", [FacilitatorsController::class, "assignSeventh"])->name("septimo.assign");

Route::get("/colaboradores", [CollaboratorsController::class, "index"])->name("colaboradores.dashboard");
Route::get("/error/register-marking", [CollaboratorsController::class, "registerMarkingError"])->name("error.register-marking");
Route::post("/register-marking", [CollaboratorsController::class, "registerMarking"])->name("register.marking");
Route::get("/colaboradores/{id}", [CollaboratorsController::class, "show"])->name("colaboradores.show");

Route::get("/horarios/crear", [ScheduleController::class, "create"])->name("horarios.create");
Route::post("/horarios/crear", [ScheduleController::class, "store"])->name("horarios.store");
Route::post("/horarios/asignar", [ScheduleController::class, "assign"])->name("horarios.assign");

Route::get("/rrhh", [RRHHController::class, "index"])->name("rrhh.dashboard");
