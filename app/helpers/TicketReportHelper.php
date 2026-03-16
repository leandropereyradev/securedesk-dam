<?php

namespace app\helpers;

class TicketReportHelper
{
  public static function getFields(): array
  {
    return [
      'ticket' => [
        'title' => 'Título:',
        'status' => 'Estado:',
        'priority' => 'Prioridad:',
        'assigned_to_username' => 'Asignado a:',
        'created_at' => 'Creado el:',
        'updated_at' => 'Última actualización:',
        'description' => 'Descripción:',
      ],
      'history' => [
        'status' => 'Estado:',
        'priority' => 'Prioridad:',
        'assigned_to' => 'Asignado a:',
      ]
    ];
  }
}
