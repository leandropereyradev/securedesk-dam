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
      'ticket-export-pdf',
      'tickets-export',
      'tickets-export-csv',
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
