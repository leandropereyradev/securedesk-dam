<?php

namespace app\routes;

use app\controllers\AuditLogsController;
use app\controllers\SessionController;

class AuditRoutes
{
  public static function auditGet()
  {
    SessionController::requireLogin();

    $data = AuditLogsController::listAll();

    $_SESSION['audits'] = $data['logs'];
    $_SESSION['usersOptions'] = $data['users'];
  }

  public static function auditPost()
  {
    SessionController::requireLogin();

    $filters = [
      'user_id' => $_POST['user_id'] ?? null,
      'action'  => $_POST['action'] ?? null
    ];

    $_SESSION['audit_filters'] = $filters;

    $data = AuditLogsController::listAll($filters);

    $_SESSION['audits'] = $data['logs'] ?? [];
    $_SESSION['usersOptions'] = $data['users'] ?? [];

    header('Location: ' . APP_URL . 'audit');
    exit;
  }
}
