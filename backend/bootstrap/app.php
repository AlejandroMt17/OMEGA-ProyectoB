<?php

/*
 * ============================================================
 * bootstrap/app.php
 * MPL-OMEGA-05 §6.3 — Manejo de Excepciones
 * ============================================================
 * Configura el kernel de la aplicación.
 * El handler de excepciones garantiza que todos los errores
 * de la API retornen JSON estructurado, nunca HTML.
 * Esto cubre el requisito RF-20 (app móvil) y el estándar
 * de respuestas JSON §6.7 del Manual de Programación Laravel.
 * ============================================================
 */

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web:      __DIR__.'/../routes/web.php',
        api:      __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health:   '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->redirectGuestsTo(fn () => route('ca.login'));
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // ── Errores de validación ─────────────────────────────────────────
        $exceptions->render(function (ValidationException $e, $request): ?JsonResponse {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'Los datos enviados no son válidos.',
                    'errors'  => $e->errors(),
                ], 422);
            }
            return null; // Dejar que el handler web lo maneje
        });

        // ── Sin autenticación ─────────────────────────────────────────────
        $exceptions->render(function (AuthenticationException $e, $request): ?JsonResponse {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => 'No autenticado. Inicia sesión para continuar.',
                ], 401);
            }
            return null;
        });

        // ── Sin autorización ──────────────────────────────────────────────
        $exceptions->render(function (AuthorizationException $e, $request): ?JsonResponse {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'No tienes permiso para realizar esta acción.',
                ], 403);
            }
            return null;
        });

        // ── Errores HTTP (404, 422, 500, …) ──────────────────────────────
        $exceptions->render(function (HttpException $e, $request): ?JsonResponse {
            if ($request->is('api/*') || $request->expectsJson()) {
                return response()->json([
                    'message' => $e->getMessage() ?: 'Error en la solicitud.',
                ], $e->getStatusCode());
            }
            return null;
        });

    })->create();
