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
      'critical-tickets',
      'unassigned-tickets',
      'profile',
      'upload',
      'comment-add',
      'logout'
    ],

    'reader' => [
      'dashboard',
      'tickets',
      'critical-tickets',
      'unassigned-tickets',
      'ticket',
      'profile',
      'logout'
    ],
  ],
];
