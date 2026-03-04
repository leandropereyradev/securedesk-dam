<?php

namespace app\controllers;

use app\controllers\ViewsController;
use app\controllers\SessionController;
use app\controllers\AuthController;
use app\controllers\TicketsController;
use app\controllers\UsersController;
use app\middlewares\RoleMiddleware;

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

  // Método principal
  public function handleRequest()
  {
    SessionController::start();

    // Construir nombre del método según vista + método HTTP
    $methodName = $this->convertViewToMethod($this->viewName, $_SERVER['REQUEST_METHOD']);

    // Ejecutar el método si existe
    if (method_exists($this, $methodName)) {
      $this->$methodName();
    }

    // Control de permisos centralizado
    RoleMiddleware::check($this->viewName);

    // Obtener ruta de la vista
    $viewPath = $this->viewsController->getViewsController($this->viewName);

    if ($viewPath === '404') {
      require_once ROOT . "app/views/content/404-view.php";
      exit;
    }

    return $viewPath;
  }

  // Convierte 'ticket-edit' + 'POST' -> 'ticketEditPost'
  private function convertViewToMethod(string $view, string $httpMethod): string
  {
    $parts = explode('-', $view);
    $parts = array_map('ucfirst', $parts);   // ['Ticket', 'Edit']
    $method = lcfirst(implode('', $parts));  // 'ticketEdit'
    return $method . ucfirst(strtolower($httpMethod)); // 'ticketEditPost'
  }

  // MÉTODOS PARA RUTAS

  // Login POST
  protected function loginPost()
  {
    AuthController::login();
  }

  // Logout GET
  protected function logoutGet()
  {
    SessionController::logout();
  }

  // Crear ticket POST
  protected function ticketCreatePost()
  {
    TicketsController::create();
  }

  // Ver ticket GET
  protected function ticketGet()
  {
    SessionController::requireLogin();
    $ticket = TicketsController::getTicketById(
      getConnection(TICKETS_DB_PATH),
      (int)$_GET['id']
    );
    $_SESSION['ticket'] = $ticket;
  }

  // Editar ticket GET
  protected function ticketEditGet()
  {
    SessionController::requireLogin();
    $_SESSION['users'] = UsersController::listAll();
  }

  // Editar ticket POST
  protected function ticketEditPost()
  {
    SessionController::requireLogin();
    $pdo = getConnection(TICKETS_DB_PATH);

    TicketsController::updateTicket(
      $pdo,
      (int)$_POST['ticket_id'],
      [
        'status'      => $_POST['status'],
        'priority'    => $_POST['priority'],
        'description' => $_POST['description'],
        'assigned_to' => $_POST['assigned_to'],
      ]
    );

    header('Location: ' . APP_URL . 'ticket?id=' . (int)$_POST['ticket_id']);
    exit;
  }
}
