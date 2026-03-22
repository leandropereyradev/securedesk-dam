<?php

namespace app\routes;

use app\controllers\AuditLogsController;
use app\controllers\SessionController;
use app\controllers\TicketsController;
use app\core\ViewContext;
use app\helpers\RedirectHelper;

class DashboardRoutes
{
  public static function dashboardGet()
  {
    SessionController::requireLogin();

    $kpiData = TicketsController::getDashboardStats();

    if (!isset($_SESSION['dashboard_logged'])) {

      AuditLogsController::logDashboardAccess(
        $_SESSION['user_id'],
        "Acceso al Dashboard"
      );

      $_SESSION['dashboard_logged'] = true;
    }

    ViewContext::set('dashboard', $kpiData);
  }

  public static function criticalTicketsGet()
  {
    SessionController::requireLogin();

    RedirectHelper::to('tickets?priority=critical');
  }

  public static function unassignedTicketsGet()
  {
    SessionController::requireLogin();

    RedirectHelper::to('tickets?assigned_to=null');
  }
}
