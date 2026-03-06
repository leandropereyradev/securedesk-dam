<?php

return [

  'public' => [
    'home',
    'login'
  ],

  'roles' => [

    // acceso total
    'admin' => [
      '*',
    ],

    'technician' => [
      'dashboard',
      'tickets',
      'ticket-create',
      'ticket-edit',
      'ticket',
      'profile',
      'comment-add',
    ],

    'reader' => [
      'dashboard',
      'tickets',
      'ticket',
      'profile',
    ],
  ],
];
