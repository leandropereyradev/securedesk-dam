<?php

namespace app\routes;

use app\controllers\AuditLogsController;
use app\controllers\SessionController;
use app\core\ViewContext;

class AuditRoutes
{
  public static function auditGet()
  {
    SessionController::requireLogin();

    $filters = [
      'user_id' => $_GET['user_id'] ?? null,
      'action' => $_GET['action'] ?? null,
    ];

    $data = AuditLogsController::listAll($filters );

    ViewContext::set('audits', $data['logs']);
    ViewContext::set('usersOptions', $data['users']);
    ViewContext::set('filters', $filters);
  }
}
