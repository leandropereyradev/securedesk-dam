<?php

namespace app\controllers;

use app\controllers\ViewsController;
use app\controllers\AuthController;

class AppController
{
  private $viewName;
  private $viewsController;

  public function __construct()
  {
    $this->viewName = isset($_GET['views']) && $_GET['views'] !== ''
      ? explode("/", $_GET['views'])[0]
      : 'home';

    $this->viewsController = new ViewsController();
  }

  public function handleRequest()
  {
    // Login: POST a login
    if ($this->viewName === 'login' && $_SERVER['REQUEST_METHOD'] === 'POST') {
      AuthController::login();
    }

    // Obtener ruta de la vista
    $viewPath = $this->viewsController->getViewsController($this->viewName);

    if ($viewPath === '404') {
      require_once ROOT . "app/views/content/404-view.php";
      exit;
    }

    return $viewPath;
  }
}
