<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ─────────────────────────────────────────────
        // 1. USUARIOS  (rol: 1=Docente, 2=Alumno)
        // ─────────────────────────────────────────────
        $docentes = [
            [
                'nombre'     => 'Carlos',
                'ap_pat'     => 'Ramírez',
                'ap_mat'     => 'Torres',
                'email'      => 'carlos.ramirez@docente.com',
                'contrasenia'=> Hash::make('password123'),
                'rol'        => 1,
            ],
            [
                'nombre'     => 'Laura',
                'ap_pat'     => 'Mendoza',
                'ap_mat'     => 'Ávila',
                'email'      => 'laura.mendoza@docente.com',
                'contrasenia'=> Hash::make('password123'),
                'rol'        => 1,
            ],
        ];

        $alumnos = [
            ['nombre'=>'Ana',      'ap_pat'=>'García',    'ap_mat'=>'López',    'email'=>'ana.garcia@alumno.com'],
            ['nombre'=>'Luis',     'ap_pat'=>'Hernández', 'ap_mat'=>'Vega',     'email'=>'luis.hernandez@alumno.com'],
            ['nombre'=>'Sofía',    'ap_pat'=>'Martínez',  'ap_mat'=>'Ruiz',     'email'=>'sofia.martinez@alumno.com'],
            ['nombre'=>'Diego',    'ap_pat'=>'Flores',    'ap_mat'=>'Soto',     'email'=>'diego.flores@alumno.com'],
            ['nombre'=>'Valentina','ap_pat'=>'Reyes',     'ap_mat'=>'Cruz',     'email'=>'valentina.reyes@alumno.com'],
            ['nombre'=>'Miguel',   'ap_pat'=>'Jiménez',   'ap_mat'=>'Mora',     'email'=>'miguel.jimenez@alumno.com'],
            ['nombre'=>'Isabella', 'ap_pat'=>'Sánchez',   'ap_mat'=>'Peña',     'email'=>'isabella.sanchez@alumno.com'],
            ['nombre'=>'Emilio',   'ap_pat'=>'Torres',    'ap_mat'=>'Castillo', 'email'=>'emilio.torres@alumno.com'],
            ['nombre'=>'Camila',   'ap_pat'=>'Vargas',    'ap_mat'=>'Ríos',     'email'=>'camila.vargas@alumno.com'],
            ['nombre'=>'Andrés',   'ap_pat'=>'Ortiz',     'ap_mat'=>'Núñez',    'email'=>'andres.ortiz@alumno.com'],
        ];

        foreach ($docentes as $d) {
            DB::table('usuarios')->insert(array_merge($d, [
                'created_at' => now(), 'updated_at' => now(),
            ]));
        }

        foreach ($alumnos as $a) {
            DB::table('usuarios')->insert(array_merge($a, [
                'contrasenia' => Hash::make('alumno123'),
                'rol'         => 2,
                'created_at'  => now(),
                'updated_at'  => now(),
            ]));
        }

        // IDs: docentes 1,2 — alumnos 3..12
        $idDocente1 = 1;
        $idDocente2 = 2;
        $idsAlumnos = range(3, 12);

        // ─────────────────────────────────────────────
        // 2. INSTITUCIONES
        // ─────────────────────────────────────────────
        DB::table('instituciones')->insert([
            [
                'id_docente'   => $idDocente1,
                'nombre'       => 'Instituto Tecnológico de Toluca',
                'logo'         => 'https://placehold.co/200x200/3B82F6/FFFFFF?text=ITT',
                'created_at'   => now(), 'updated_at' => now(),
            ],
            [
                'id_docente'   => $idDocente2,
                'nombre'       => 'Universidad Autónoma del Estado de México',
                'logo'         => 'https://placehold.co/200x200/8B5CF6/FFFFFF?text=UAEM',
                'created_at'   => now(), 'updated_at' => now(),
            ],
            [
                'id_docente'   => $idDocente2,
                'nombre'       => 'Universidad del Valle de México',
                'logo'         => 'https://placehold.co/200x200/10B981/FFFFFF?text=UVM',
                'created_at'   => now(), 'updated_at' => now(),
            ],
        ]);

        // IDs: inst1=1, inst2=2

        // ─────────────────────────────────────────────
        // 3. RUBROS DE EVALUACIÓN
        // ─────────────────────────────────────────────
        DB::table('rubros_evaluacion')->insert([
            // Institución 1
            ['id_institucion'=>1, 'nombre'=>'Asistencia',    'porcentaje_minimo'=>80.00, 'created_at'=>now(),'updated_at'=>now()],
            ['id_institucion'=>1, 'nombre'=>'Participación', 'porcentaje_minimo'=>70.00, 'created_at'=>now(),'updated_at'=>now()],
            ['id_institucion'=>1, 'nombre'=>'Puntualidad',   'porcentaje_minimo'=>75.00, 'created_at'=>now(),'updated_at'=>now()],
            // Institución 2 — UAEM
            ['id_institucion'=>2, 'nombre'=>'Asistencia',    'porcentaje_minimo'=>85.00, 'created_at'=>now(),'updated_at'=>now()],
            ['id_institucion'=>2, 'nombre'=>'Actividades',   'porcentaje_minimo'=>60.00, 'created_at'=>now(),'updated_at'=>now()],
            // Institución 3 — UVM
            ['id_institucion'=>3, 'nombre'=>'Asistencia',    'porcentaje_minimo'=>80.00, 'created_at'=>now(),'updated_at'=>now()],
            ['id_institucion'=>3, 'nombre'=>'Tareas',        'porcentaje_minimo'=>65.00, 'created_at'=>now(),'updated_at'=>now()],
        ]);

        // ─────────────────────────────────────────────
        // 4. GRUPOS
        // ─────────────────────────────────────────────
        DB::table('grupos')->insert([
            [
                'id_institucion' => 1,
                'id_docente'     => $idDocente1,
                'nombre'         => 'Grupo A',
                'materia'        => 'Programación Web',
                'periodo'        => '2026-1',
                'no_alumnos'     => 6,
                'codigo_inv'     => 'PWEB-2026A',
                'created_at'     => now(), 'updated_at' => now(),
            ],
            [
                'id_institucion' => 1,
                'id_docente'     => $idDocente1,
                'nombre'         => 'Grupo B',
                'materia'        => 'Bases de Datos',
                'periodo'        => '2026-1',
                'no_alumnos'     => 4,
                'codigo_inv'     => 'BD-2026B',
                'created_at'     => now(), 'updated_at' => now(),
            ],
            [
                'id_institucion' => 2,
                'id_docente'     => $idDocente2,
                'nombre'         => 'Grupo Único',
                'materia'        => 'Matemáticas Discretas',
                'periodo'        => '2026-1',
                'no_alumnos'     => 5,
                'codigo_inv'     => 'MATD-2026',
                'created_at'     => now(), 'updated_at' => now(),
            ],
            [
                'id_institucion' => 3,
                'id_docente'     => $idDocente2,
                'nombre'         => 'Grupo Alpha',
                'materia'        => 'Ingeniería de Software',
                'periodo'        => '2026-1',
                'no_alumnos'     => 4,
                'codigo_inv'     => 'IS-2026A',
                'created_at'     => now(), 'updated_at' => now(),
            ],
        ]);

        // IDs: grupo1=1, grupo2=2, grupo3=3

        // ─────────────────────────────────────────────
        // 5. GRUPO_ALUMNOS
        // ─────────────────────────────────────────────
        // Grupo 1 → alumnos 3,4,5,6,7,8
        $grupo1Alumnos = [3,4,5,6,7,8];
        foreach ($grupo1Alumnos as $idAlumno) {
            DB::table('grupo_alumnos')->insert([
                'id_grupo'       => 1,
                'id_alumno'      => $idAlumno,
                'fec_inscripcion'=> '2026-02-01',
                'created_at'     => now(), 'updated_at' => now(),
            ]);
        }

        // Grupo 2 → alumnos 9,10,11,12
        $grupo2Alumnos = [9,10,11,12];
        foreach ($grupo2Alumnos as $idAlumno) {
            DB::table('grupo_alumnos')->insert([
                'id_grupo'       => 2,
                'id_alumno'      => $idAlumno,
                'fec_inscripcion'=> '2026-02-01',
                'created_at'     => now(), 'updated_at' => now(),
            ]);
        }

        // Grupo 3 → alumnos 3,4,5,6,7
        $grupo3Alumnos = [3,4,5,6,7];
        foreach ($grupo3Alumnos as $idAlumno) {
            DB::table('grupo_alumnos')->insert([
                'id_grupo'       => 3,
                'id_alumno'      => $idAlumno,
                'fec_inscripcion'=> '2026-02-03',
                'created_at'     => now(), 'updated_at' => now(),
            ]);
        }

        // Grupo 4 (UVM) → alumnos 8,9,10,11
        $grupo4Alumnos = [8,9,10,11];
        foreach ($grupo4Alumnos as $idAlumno) {
            DB::table('grupo_alumnos')->insert([
                'id_grupo'       => 4,
                'id_alumno'      => $idAlumno,
                'fec_inscripcion'=> '2026-02-05',
                'created_at'     => now(), 'updated_at' => now(),
            ]);
        }

        // ─────────────────────────────────────────────
        // 6. SESIONES  (est_sesion: 1=Activa, 0=Cerrada)
        // ─────────────────────────────────────────────
        $sesionesData = [
            // Grupo 1 — 5 sesiones, la última activa
            ['id_grupo'=>1,'clave'=>'S1G1','est'=>0,'fecha'=>'2026-03-03','apertura'=>'2026-03-03 08:00:00','cierre'=>'2026-03-03 10:00:00'],
            ['id_grupo'=>1,'clave'=>'S2G1','est'=>0,'fecha'=>'2026-03-10','apertura'=>'2026-03-10 08:00:00','cierre'=>'2026-03-10 10:00:00'],
            ['id_grupo'=>1,'clave'=>'S3G1','est'=>0,'fecha'=>'2026-03-17','apertura'=>'2026-03-17 08:00:00','cierre'=>'2026-03-17 10:00:00'],
            ['id_grupo'=>1,'clave'=>'S4G1','est'=>0,'fecha'=>'2026-04-07','apertura'=>'2026-04-07 08:00:00','cierre'=>'2026-04-07 10:00:00'],
            ['id_grupo'=>1,'clave'=>'S5G1','est'=>1,'fecha'=>'2026-05-05','apertura'=>'2026-05-05 08:00:00','cierre'=>null],
            // Grupo 2 — 3 sesiones cerradas
            ['id_grupo'=>2,'clave'=>'S1G2','est'=>0,'fecha'=>'2026-03-04','apertura'=>'2026-03-04 10:00:00','cierre'=>'2026-03-04 12:00:00'],
            ['id_grupo'=>2,'clave'=>'S2G2','est'=>0,'fecha'=>'2026-03-11','apertura'=>'2026-03-11 10:00:00','cierre'=>'2026-03-11 12:00:00'],
            ['id_grupo'=>2,'clave'=>'S3G2','est'=>0,'fecha'=>'2026-04-08','apertura'=>'2026-04-08 10:00:00','cierre'=>'2026-04-08 12:00:00'],
            // Grupo 3 (UAEM) — 3 sesiones, la última activa
            ['id_grupo'=>3,'clave'=>'S1G3','est'=>0,'fecha'=>'2026-03-05','apertura'=>'2026-03-05 09:00:00','cierre'=>'2026-03-05 11:00:00'],
            ['id_grupo'=>3,'clave'=>'S2G3','est'=>0,'fecha'=>'2026-03-12','apertura'=>'2026-03-12 09:00:00','cierre'=>'2026-03-12 11:00:00'],
            ['id_grupo'=>3,'clave'=>'S3G3','est'=>1,'fecha'=>'2026-05-07','apertura'=>'2026-05-07 09:00:00','cierre'=>null],
            // Grupo 4 (UVM) — 2 sesiones cerradas
            ['id_grupo'=>4,'clave'=>'S1G4','est'=>0,'fecha'=>'2026-03-06','apertura'=>'2026-03-06 11:00:00','cierre'=>'2026-03-06 13:00:00'],
            ['id_grupo'=>4,'clave'=>'S2G4','est'=>0,'fecha'=>'2026-04-10','apertura'=>'2026-04-10 11:00:00','cierre'=>'2026-04-10 13:00:00'],
        ];

        foreach ($sesionesData as $s) {
            DB::table('sesiones')->insert([
                'id_grupo'     => $s['id_grupo'],
                'clave'        => $s['clave'],
                'est_sesion'   => $s['est'],
                'fec_sesion'   => $s['fecha'],
                'hora_apertura'=> $s['apertura'],
                'hora_cierre'  => $s['cierre'],
                'created_at'   => now(), 'updated_at' => now(),
            ]);
        }

        // IDs sesiones: 1..5 grupo1(ITT), 6..8 grupo2(ITT), 9..11 grupo3(UAEM), 12..13 grupo4(UVM)

        // ─────────────────────────────────────────────
        // 7. ASISTENCIAS  (1=Presente, 2=Ausente, 3=Justificada)
        // ─────────────────────────────────────────────
        // Sesiones cerradas de grupo 1 (id 1..4) con alumnos [3,4,5,6,7,8]
        $asistenciasGrupo1 = [
            // sesion_id => [id_alumno => estado]
            1 => [3=>1,4=>1,5=>1,6=>2,7=>1,8=>3],
            2 => [3=>1,4=>2,5=>1,6=>1,7=>1,8=>1],
            3 => [3=>1,4=>1,5=>2,6=>1,7=>3,8=>1],
            4 => [3=>2,4=>1,5=>1,6=>1,7=>1,8=>1],
        ];

        foreach ($asistenciasGrupo1 as $sesionId => $alumnos) {
            foreach ($alumnos as $alumnoId => $estado) {
                DB::table('asistencias')->insert([
                    'id_sesion'     => $sesionId,
                    'id_alumno'     => $alumnoId,
                    'est_asistencia'=> $estado,
                    'hora_registro' => $estado == 1 ? Carbon::parse($sesionesData[$sesionId-1]['apertura'])->addMinutes(rand(1,15)) : null,
                    'created_at'    => now(), 'updated_at' => now(),
                ]);
            }
        }

        // Sesiones cerradas de grupo 2 (id 6,7,8) con alumnos [9,10,11,12]
        $asistenciasGrupo2 = [
            6 => [9=>1,10=>1,11=>1,12=>2],
            7 => [9=>1,10=>3,11=>1,12=>1],
            8 => [9=>2,10=>1,11=>1,12=>1],
        ];

        foreach ($asistenciasGrupo2 as $sesionId => $alumnos) {
            foreach ($alumnos as $alumnoId => $estado) {
                DB::table('asistencias')->insert([
                    'id_sesion'     => $sesionId,
                    'id_alumno'     => $alumnoId,
                    'est_asistencia'=> $estado,
                    'hora_registro' => $estado == 1 ? Carbon::parse($sesionesData[$sesionId-1]['apertura'])->addMinutes(rand(1,20)) : null,
                    'created_at'    => now(), 'updated_at' => now(),
                ]);
            }
        }

        // Sesión cerrada de grupo 3 (id 9,10) con alumnos [3,4,5,6,7]
        $asistenciasGrupo3 = [
            9  => [3=>1,4=>1,5=>1,6=>1,7=>2],
            10 => [3=>1,4=>1,5=>3,6=>2,7=>1],
        ];

        foreach ($asistenciasGrupo3 as $sesionId => $alumnos) {
            foreach ($alumnos as $alumnoId => $estado) {
                DB::table('asistencias')->insert([
                    'id_sesion'     => $sesionId,
                    'id_alumno'     => $alumnoId,
                    'est_asistencia'=> $estado,
                    'hora_registro' => $estado == 1 ? Carbon::parse($sesionesData[$sesionId-1]['apertura'])->addMinutes(rand(1,25)) : null,
                    'created_at'    => now(), 'updated_at' => now(),
                ]);
            }
        }

        // Sesiones cerradas de grupo 4 / UVM (id 12,13) con alumnos [8,9,10,11]
        $asistenciasGrupo4 = [
            12 => [8=>1,9=>1,10=>2,11=>1],
            13 => [8=>1,9=>3,10=>1,11=>1],
        ];

        foreach ($asistenciasGrupo4 as $sesionId => $alumnos) {
            foreach ($alumnos as $alumnoId => $estado) {
                DB::table('asistencias')->insert([
                    'id_sesion'     => $sesionId,
                    'id_alumno'     => $alumnoId,
                    'est_asistencia'=> $estado,
                    'hora_registro' => $estado == 1 ? now()->subDays(rand(5,30))->setTime(rand(8,10), rand(1,25)) : null,
                    'created_at'    => now(), 'updated_at' => now(),
                ]);
            }
        }

        // ─────────────────────────────────────────────
        // 8. SUSCRIPCIONES  (plan: 1=Básico, 2=Mensual | est: 1=Activa, 2=Vencida, 3=En gracia)
        // ─────────────────────────────────────────────
        DB::table('suscripciones')->insert([
            [
                'id_usuario'       => $idDocente1,
                'plan'             => 2, // Mensual
                'est_suscripcion'  => 1, // Activa
                'fec_inicio'       => '2026-04-01',
                'fec_fin'          => '2026-05-01',
                'fec_ultimo_pago'  => '2026-04-01',
                'created_at'       => now(), 'updated_at' => now(),
            ],
            [
                'id_usuario'       => $idDocente2,
                'plan'             => 1, // Básico
                'est_suscripcion'  => 3, // En gracia
                'fec_inicio'       => '2026-03-01',
                'fec_fin'          => '2026-04-01',
                'fec_ultimo_pago'  => '2026-03-01',
                'created_at'       => now(), 'updated_at' => now(),
            ],
        ]);

        // IDs suscripciones: 1 (docente1), 2 (docente2)

        // ─────────────────────────────────────────────
        // 9. PAGOS
        // ─────────────────────────────────────────────
        DB::table('pagos')->insert([
            [
                'id_suscripcion'       => 1,
                'paypal_order_id'      => 'PP-ORD-20260401-001',
                'paypal_transaction_id'=> 'PP-TXN-20260401-001',
                'mon_monto'            => 99.00,
                'est_pago'             => 'COMPLETED',
                'fec_pago'             => '2026-04-01',
                'tipo_pago'            => 'paypal',
                'created_at'           => now(), 'updated_at' => now(),
            ],
            [
                'id_suscripcion'       => 2,
                'paypal_order_id'      => 'PP-ORD-20260301-002',
                'paypal_transaction_id'=> 'PP-TXN-20260301-002',
                'mon_monto'            => 49.00,
                'est_pago'             => 'COMPLETED',
                'fec_pago'             => '2026-03-01',
                'tipo_pago'            => 'card',
                'created_at'           => now(), 'updated_at' => now(),
            ],
            [
                'id_suscripcion'       => 1,
                'paypal_order_id'      => 'PP-ORD-20260302-003',
                'paypal_transaction_id'=> null,
                'mon_monto'            => 99.00,
                'est_pago'             => 'PENDING',
                'fec_pago'             => '2026-04-30',
                'tipo_pago'            => 'oxxo',
                'created_at'           => now(), 'updated_at' => now(),
            ],
        ]);
    }
}
