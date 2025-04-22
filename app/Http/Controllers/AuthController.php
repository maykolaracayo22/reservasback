<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registro de nuevo usuario.
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed',
            'role' => 'required|string|in:cliente,emprendedor,super-admin',
        ]);

        // Crear el usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Asignar el rol al usuario
        if (Role::where('name', $request->role)->exists()) {
            $user->assignRole($request->role);
        } else {
            return response()->json(['error' => 'Rol no válido.'], 400);
        }

        // Crear un token para el usuario
        $token = $user->createToken('Token')->plainTextToken;

        return response()->json([
            'message' => 'Registro exitoso',
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'token' => $token,
        ]);
    }

    /**
     * Login de usuario.
     */
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ]);

        // Intentar hacer login
        if (!Auth::attempt($request->only('email', 'password'))) {
            throw ValidationException::withMessages([
                'email' => ['Credenciales incorrectas'],
            ]);
        }

        $user = Auth::user();

        // Verificar que el usuario tiene un rol asignado
        if ($user->getRoleNames()->isEmpty()) {
            return response()->json(['message' => 'Este usuario no tiene un rol asignado.'], 403);
        }

        // Crear un token para el usuario
        $token = $user->createToken('Token')->plainTextToken;

        return response()->json([
            'message' => 'Login exitoso',
            'user' => $user,
            'roles' => $user->getRoleNames(),
            'token' => $token,
        ]);
    }

    /**
     * Logout de usuario.
     */
    public function logout(Request $request)
    {
        // Verificar que el usuario está autenticado
        if (Auth::check()) {
            // Eliminar todos los tokens de sesión
            Auth::user()->tokens()->delete();
            return response()->json(['message' => 'Sesión cerrada exitosamente']);
        }

        return response()->json(['message' => 'No autenticado'], 401);
    }

    /**
     * Obtener rol de un usuario específico por su ID.
     */
    public function getUserRole($id)
    {
        // Buscar el usuario por su ID
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'Usuario no encontrado'], 404);
        }

        // Devolver el nombre del usuario y sus roles
        return response()->json([
            'user' => $user->name,
            'roles' => $user->getRoleNames(),
        ]);
    }
}
