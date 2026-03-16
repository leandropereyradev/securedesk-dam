<?php

namespace app\helpers;

class TicketsListViewHelper
{
  public static function getFilters(): array
  {
    return [
      [
        'name' => 'status',
        'label' => 'Estado',
        'all_label' => 'Todos',
        'options' => [
          'new' => 'Nuevo',
          'in_process' => 'En proceso',
          'resolved' => 'Resuelto',
        ]
      ],
      [
        'name' => 'priority',
        'label' => 'Prioridad',
        'all_label' => 'Todas',
        'options' => [
          'low' => 'Baja',
          'medium' => 'Media',
          'high' => 'Alta',
          'critical' => 'Crítica'
        ]
      ]
    ];
  }

  public static function getColumns(): array
  {
    return [
      [
        'label' => 'Título',
        'field' => 'title'
      ],
      [
        'label' => 'Estado',
        'field' => 'status'
      ],
      [
        'label' => 'Prioridad',
        'field' => 'priority'
      ],
      [
        'label' => 'Fecha de creación',
        'field' => 'created_at'
      ],
      [
        'label' => 'Asignado a',
        'field' => 'assigned_to_username'
      ],
      [
        'label' => 'Acciones',
        'render' => function ($ticket) {
          return '<a href="' . APP_URL . 'ticket?id=' . (int)$ticket['id'] . '" class="button">Ver</a>';
        }
      ]
    ];
  }
}
