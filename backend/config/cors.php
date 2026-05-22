<?php

/*
 * ================================================================
 * config/cors.php — CORS para API REST
 * MPL-OMEGA-05 §6.5 — Cliente HTTP (Consumo de APIs)
 * RNF-W-42 — Seguridad en cabeceras
 * ================================================================
 * Permite que la app Flutter consuma la API desde:
 *   - Emulador Android: http://10.0.2.2:8000
 *   - Simulador iOS:    http://127.0.0.1:8000
 *   - Dispositivo físico: la IP local del servidor XAMPP
 *
 * En producción, reemplazar 'allowed_origins' => ['*']
 * por los dominios/IPs reales del servidor.
 * ================================================================
 */

return [

    'paths' => ['api/*', 'sanctum/csrf-cookie'],

    'allowed_methods' => ['*'],

    /*
     * Durante desarrollo local se permite cualquier origen para
     * facilitar las pruebas desde Flutter en emulador o dispositivo.
     * En producción cambiar por el dominio real: ['https://midominio.com']
     */
    'allowed_origins' => ['*'],

    'allowed_origins_patterns' => [],

    'allowed_headers' => ['*'],

    'exposed_headers' => [],

    'max_age' => 0,

    /*
     * false para APIs stateless con Sanctum token (Bearer).
     * Solo se activa true en flujos web con cookies de sesión.
     */
    'supports_credentials' => false,

];
