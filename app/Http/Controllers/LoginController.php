<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            $user = Auth::user();
            switch ($user->role) {
                case "collaborator":
                    return redirect()->route("colaboradores.dashboard");
                case "facilitator":
                    return redirect()->route("facilitadores.dashboard");
                case "rrhh":
                    return redirect()->route("rrhh.dashboard");
                default:
                    return redirect()->route("login");
            }
        }

        return view("login");
    }

    public function validate(Request $request)
    {
        $request->validate([
            "cod_empleado" => "required|string|max:10",
            "password" => "required|string|min:8",
        ]);

        $credentials = $request->only("cod_empleado", "password");
        $user = User::where("cod_user", $credentials["cod_empleado"])->first();

        if (!$user || !Hash::check($credentials["password"], $user->password)) {
            return redirect()->back()->with("error", "Credenciales inválidas");
        }

        Auth::login($user);

        switch ($user->role) {
            case "collaborator":
                return redirect()->route("colaboradores.dashboard");
            case "facilitator":
                return redirect()->route("facilitadores.dashboard");
            case "rrhh":
                return redirect()->route("rrhh.dashboard");
            default:
                return redirect()->route("login");
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route("login");
    }
}