<?php

namespace app\controllers;

use app\middlewares\RoleMiddleware;
use app\core\RouteRegistry;
use app\helpers\ErrorPageHelper;
use app\helpers\SecurityHelper;

class AppController
{
  private string $viewName;
  private ViewsController $viewsController;

  public function __construct()
  {
    $this->viewName = isset($_GET['views']) && $_GET['views'] !== ''
      ? explode("/", $_GET['views'])[0]
      : 'home';

    $this->viewsController = new ViewsController();
  }

  public function handleRequest()
  {
    SessionController::start();

    SecurityHelper::verifyCsrf();

    $methodName = $this->convertViewToMethod(
      $this->viewName,
      $_SERVER['REQUEST_METHOD']
    );

    RouteRegistry::dispatch($methodName);

    $viewPath = $this->viewsController->getViewsController($this->viewName);

    if ($viewPath === '404') {
      return ErrorPageHelper::show(404);
    }

    if (!RoleMiddleware::check($this->viewName)) {

      if (!isset($_SESSION['user_id'])) {
        header("Location: login");
        exit;
      }

      return ErrorPageHelper::show(403);
    }

    return $viewPath;
  }

  private function convertViewToMethod(string $view, string $httpMethod): string
  {
    $parts = explode('-', $view);
    $parts = array_map('ucfirst', $parts);

    $method = lcfirst(implode('', $parts));

    return $method . ucfirst(strtolower($httpMethod));
  }
}
