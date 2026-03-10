<?php

return [
  [
    'label' => 'Inicio',
    'route' => '',
    // visible siempre (si hay sesión)
    'permission' => null,
  ],
  [
    'label' => 'Dashboard',
    'route' => 'dashboard',
    'permission' => 'dashboard',
  ],
  [
    'label' => 'Tickets',
    'route' => 'tickets',
    'permission' => 'tickets',
    'children' => [
      [
        'label' => 'Crear Ticket',
        'route' => 'ticket-create',
        'permission' => 'ticket-create',
      ],
    ],
  ],
  [
    'label' => 'Usuarios',
    'route' => 'users',
    'permission' => 'users',
  ],
  [
    'label' => 'Auditoría',
    'route' => 'audit',
    'permission' => 'audit',
  ],
  [
    'label' => 'Mi Cuenta',
    'route' => 'profile',
    'permission' => 'profile',
  ],
];
