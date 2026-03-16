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
      'ticket-report',
      'profile',
      'upload',
      'comment-add',
      'logout'
    ],

    'reader' => [
      'dashboard',
      'tickets',
      'ticket',
      'profile',
      'logout'
    ],
  ],
];
