// ============================================================
// Company    : OMEGA Solutions (OMEGA)
// Project    : ATN - Sistema de Control de Asistencias
// File       : sesion_historial_model.dart
// Created on : 27/04/2026
// Created by : Jorge Alejandro Martinez Toris
// Reviewed by:
// ------------------------------------------------------------
// Changelog:
//   [001] 27/04/2026 - Jorge Alejandro Martinez Toris - Modelo de sesion para historial
//   [002] 07/05/2026 - Jorge Alejandro Martinez Toris - Ajuste campos backend real
// ============================================================
class SesionHistorialModel
{
  final int     id;
  final String  fecha;
  final String  horaApertura;
  final String? horaCierre;
  final int     totalAlumnos;
  final int     presentes;
  final int     faltas;
  final int     justificadas;

  const SesionHistorialModel({
    required this.id,
    required this.fecha,
    required this.horaApertura,
    this.horaCierre,
    required this.totalAlumnos,
    required this.presentes,
    required this.faltas,
    required this.justificadas,
  });

  double get porcentajeAsistencia
  {
    if (totalAlumnos == 0) return 0;
    return ((presentes + justificadas) / totalAlumnos) * 100;
  }

  factory SesionHistorialModel.fromJson(Map<String, dynamic> json)
  {
    return SesionHistorialModel(
      id:           json['id_sesion']     as int,
      fecha:        json['fec_sesion']    as String,
      horaApertura: _formatHora(json['hora_apertura'] as String),
horaCierre:   json['hora_cierre'] != null
    ? _formatHora(json['hora_cierre'] as String)
    : null,
      totalAlumnos: json['total_alumnos'] as int,
      presentes:    json['presentes']     as int,
      faltas:       json['faltas']        as int,
      justificadas: json['justificadas']  as int,
    );
  }
  static String _formatHora(String iso)
  {
    try {
      final dt = DateTime.parse(iso);
      final h  = dt.hour.toString().padLeft(2, '0');
      final m  = dt.minute.toString().padLeft(2, '0');
      return '$h:$m';
    } catch (_) {
      return iso;
    }
  }
}