<?php

namespace app\routes;

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
