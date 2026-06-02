<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Controlador HTTP — Autenticación de Docentes y Alumnos.
 * Sin lógica de negocio, solo delega al AuthService.
 */
class AuthController extends Controller
{
    public function __construct(
        private readonly AuthService $auth
    ) {}

    public function registro(Request $request): JsonResponse
    {
        $resultado = $this->auth->registro($request->all());
        return response()->json(['data' => $resultado], 201);
    }

    public function login(Request $request): JsonResponse
    {
        $resultado = $this->auth->login($request->all());
        return response()->json(['data' => $resultado]);
    }

    public function logout(Request $request): JsonResponse
    {
        $this->auth->logout($request->user());
        return response()->noContent();
    }

    public function me(Request $request): JsonResponse
    {
        return response()->json([
            'data' => $this->auth->me($request->user()),
        ]);
    }
}