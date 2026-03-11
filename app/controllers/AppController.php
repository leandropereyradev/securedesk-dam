<?php

namespace app\controllers;

use app\middlewares\RoleMiddleware;
use app\core\RouteRegistry;
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

    // Ejecuta la ruta correspondiente si existe
    RouteRegistry::dispatch($methodName);

    // Verifica permisos de rol
    RoleMiddleware::check($this->viewName);

    // Obtiene la vista
    $viewPath = $this->viewsController->getViewsController($this->viewName);

    if ($viewPath === '404') {
      require_once ROOT . "app/views/content/404-view.php";
      exit;
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
