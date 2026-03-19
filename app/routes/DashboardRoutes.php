<?php

namespace app\routes;

use app\controllers\SessionController;
use app\controllers\TicketsController;

class DashboardRoutes
{
  public static function dashboardGet()
  {
    SessionController::requireLogin();

    $kpiData = TicketsController::getDashboardStats();

    $_SESSION['dashboard'] = $kpiData;
  }
}
