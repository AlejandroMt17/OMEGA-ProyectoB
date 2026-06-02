<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Repositories\Contracts\UsuarioRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

/**
 * Controlador Web — Perfil del Docente autenticado.
 * @version 1.0.0
 */
class PerfilWebController extends Controller
{
    public function __construct(
        private readonly UsuarioRepositoryInterface $usuarios,
    ) {}

    public function index()
    {
        $usuario = Auth::user();
        return view('modules.perfil.index', compact('usuario'));
    }

    public function actualizar(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'nombre' => ['required', 'string', 'max:100'],
            'ap_pat' => ['required', 'string', 'max:100'],
            'ap_mat' => ['required', 'string', 'max:100'],
            'email'  => ['required', 'email', 'max:200', 'unique:usuarios,email,' . $usuario->id_usuario . ',id_usuario'],
        ], [
            'nombre.required' => 'El campo nombre es obligatorio',
            'ap_pat.required' => 'El campo apellido paterno es obligatorio',
            'ap_mat.required' => 'El campo apellido materno es obligatorio',
            'email.required'  => 'El campo correo electrónico es obligatorio',
            'email.email'     => 'El correo no tiene un formato válido',
            'email.unique'    => 'El correo ya está registrado',
        ]);

        $this->usuarios->guardar($usuario, [
            'nombre' => $request->nombre,
            'ap_pat' => $request->ap_pat,
            'ap_mat' => $request->ap_mat,
            'email'  => $request->email,
        ]);

        return redirect()->route('ca.perfil.index')
            ->with('success', 'La información se actualizó correctamente');
    }

    public function cambiarContrasenia(Request $request)
    {
        $usuario = Auth::user();

        $request->validate([
            'contrasenia_actual'          => ['required', 'string'],
            'contrasenia_nueva'           => ['required', 'string', 'min:8', 'confirmed'],
            'contrasenia_nueva_confirmation' => ['required'],
        ], [
            'contrasenia_actual.required'  => 'El campo contraseña actual es obligatorio',
            'contrasenia_nueva.required'   => 'El campo contraseña nueva es obligatorio',
            'contrasenia_nueva.min'        => 'La contraseña nueva debe tener al menos 8 caracteres',
            'contrasenia_nueva.confirmed'  => 'Los campos contraseña nueva y confirmar contraseña deben coincidir',
        ]);

        if (!Hash::check($request->contrasenia_actual, $usuario->contrasenia)) {
            throw ValidationException::withMessages([
                'contrasenia_actual' => 'La contraseña actual no es correcta',
            ]);
        }

        $this->usuarios->guardar($usuario, [
            'contrasenia' => $request->contrasenia_nueva,
        ]);

        return redirect()->route('ca.perfil.index')
            ->with('success', 'La información se actualizó correctamente');
    }
}