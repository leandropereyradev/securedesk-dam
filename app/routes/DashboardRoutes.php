<?php

namespace app\routes;

use app\controllers\SessionController;
use app\controllers\TicketsController;
use app\helpers\RedirectHelper;

class DashboardRoutes
{
  public static function dashboardGet()
  {
    SessionController::requireLogin();

    $kpiData = TicketsController::getDashboardStats();

    $_SESSION['dashboard'] = $kpiData;
  }

  public static function criticalTicketsGet()
  {
    SessionController::requireLogin();

    $_SESSION['tickets_filters'] = [
      'status' => null,
      'priority' => 'critical'
    ];

    RedirectHelper::to('tickets');
  }

  public static function unassignedTicketsGet()
  {
    SessionController::requireLogin();

    $_SESSION['tickets_filters'] = [
      'status' => null,
      'priority' => null,
      'assigned_to' => 'null'
    ];

    RedirectHelper::to('tickets');
  }
}
