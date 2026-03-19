<?php

namespace app\helpers;

class KpiHelper
{
  public static function getFields(): array
  {
    return [
      'stats' => [
        'total' => 'Total:',
        'new' => 'Nuevos:',
        'in_process' => 'En proceso:',
        'resolved' => 'Resueltos:'
      ],
      'priorities' => [
        'low' => 'Baja:',
        'medium' => 'Media:',
        'high' => 'Alta:',
        'critical' => 'Crítica:',
      ],
      'graphics' => [
        'category' => [
          'label' => 'Distribución por Categoría',
          'field' => 'category'
        ],
        'status' => [
          'label' => 'Distribución por Estado',
          'field' => 'status'
        ]
      ]
    ];
  }
}
