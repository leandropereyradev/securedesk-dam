<?php

namespace app\helpers;

class AuditViewHelper
{
  public static function getFilters(array $usersOptions): array
  {
    return [
      [
        'name' => 'user_id',
        'label' => 'Usuario',
        'all_label' => 'Todos',
        'options' => $usersOptions
      ],
      [
        'name' => 'action',
        'label' => 'Acción',
        'all_label' => 'Todas',
        'options' => [
          'login' => 'Login',
          'logout' => 'Logout',
          'login_blocked' => 'Bloqueos de usuario',
          'ticket_create' => 'Tickets Creados',
          'ticket_update' => 'Tickets Editados',
          'export' => 'Esportaciones',
          'search' => 'Búsqueda explícita',
          'access' => 'Acceso a Dashboard',
          'add_comment' => 'Comentarios añadidos'
        ]
      ]
    ];
  }

  public static function getColumns(): array
  {
    return [
      [
        'label' => 'Fecha',
        'field' => 'created_at'
      ],
      [
        'label' => 'Usuario',
        'render' => function ($audit) {
          return SecurityHelper::escapeXSS($audit['username'] ?? 'Sistema');
        }
      ],
      [
        'label' => 'Acción',
        'field' => 'action'
      ],
      [
        'label' => 'Entidad',
        'field' => 'entity'
      ],
      [
        'label' => 'Detalle',
        'render' => function ($audit) {
          return !empty($audit['details'])
            ? SecurityHelper::escapeXSS($audit['details'])
            : 'Sin detalle';
        }
      ]
    ];
  }
}
