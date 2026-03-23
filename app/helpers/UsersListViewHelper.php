<?php

namespace app\helpers;

class UsersListViewHelper
{
  public static function getFilters(): array
  {

    return [
      [
        'name' => 'role',
        'label' => 'Rol',
        'all_label' => 'Todos',
        'options' => [
          'admin' => 'Administrador',
          'technician' => 'Técnico',
          'reader' => 'Lector',
        ]
      ]
    ];
  }

  public static function getColumns(): array
  {
    return [
      [
        'label' => 'ID',
        'field' => 'id'
      ],
      [
        'label' => 'Nombre de usuario',
        'field' => 'username'
      ],
      [
        'label' => 'Rol',
        'field' => 'role'
      ],
      [
        'label' => 'Fecha de creación',
        'field' => 'created_at'
      ]
    ];
  }
}
