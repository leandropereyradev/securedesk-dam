<?php

namespace app\controllers;

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

  // MÉTODO PRINCIPAL
  public function handleRequest()
  {
    SessionController::start();

    $methodName = $this->convertViewToMethod(
      $this->viewName,
      $_SERVER['REQUEST_METHOD']
    );

    if (method_exists($this, $methodName)) {
      $this->$methodName();
    }

    RoleMiddleware::check($this->viewName);

    $viewPath = $this->viewsController->getViewsController($this->viewName);

    if ($viewPath === '404') {
      require_once ROOT . "app/views/content/404-view.php";
      exit;
    }

    return $viewPath;
  }

  // Convierte "ticket-edit" + POST -> ticketEditPost
  private function convertViewToMethod(string $view, string $httpMethod): string
  {
    $parts = explode('-', $view);
    $parts = array_map('ucfirst', $parts);

    $method = lcfirst(implode('', $parts));

    return $method . ucfirst(strtolower($httpMethod));
  }

  // Métodos para login/logout
  protected function loginPost()
  {
    AuthController::login();
  }

  protected function logoutGet()
  {
    SessionController::logout();
  }


  // Métodos para tickets
  protected function ticketsGet()
  {
    SessionController::requireLogin();

    $tickets = TicketsController::listAll();

    $_SESSION['tickets'] = $tickets;
  }

  protected function ticketCreatePost()
  {
    $result = TicketsController::create();

    if (!$result['success']) {
      $_SESSION['ticket_error'] = $result['error'];
    }

    header('Location: tickets');
    exit;
  }

  protected function ticketGet()
  {
    SessionController::requireLogin();

    $ticketId = (int)$_GET['id'];

    $ticket = TicketsController::getTicketById($ticketId);

    if (!$ticket) {
      header('Location: tickets');
      exit;
    }

    $ticket['attachments'] = AttachmentsController::getAttachmentsByTicket($ticketId);

    $ticket['comments'] = TicketCommentsController::listAll($ticketId);

    $ticket['history'] = TicketHistoryController::listHistory(
      $ticketId
    );

    $_SESSION['ticket'] = $ticket;
  }

  protected function ticketEditGet()
  {
    SessionController::requireLogin();

    $_SESSION['users'] = UsersController::listAll();
  }

  protected function ticketEditPost()
  {
    SessionController::requireLogin();

    $ticketId = (int)($_POST['ticket_id'] ?? 0);

    $data = [
      'status'      => $_POST['status'] ?? null,
      'priority'    => $_POST['priority'] ?? null,
      'assigned_to' => $_POST['assigned_to'] ?? null,
      'description' => $_POST['description'] ?? null
    ];

    $result = TicketsController::updateTicket(
      $ticketId,
      $_SESSION['user_id'],
      $data
    );

    if (!$result['success']) {
      $_SESSION['ticket_error'] = $result['error'];
    }

    header("Location: ticket?id={$ticketId}");
    exit;
  }

  // Métodos para attachments
  protected function uploadPost()
  {
    SessionController::requireLogin();

    $ticketId = (int)$_POST['ticket_id'];

    AttachmentsController::upload($ticketId);

    header('Location: ' . APP_URL . 'ticket?id=' . $ticketId);
    exit;
  }

  protected function attachmentDownloadGet()
  {
    SessionController::requireLogin();

    AttachmentsController::download((int)$_GET['id']);
  }


  protected function commentAddPost()
  {
    SessionController::requireLogin();

    TicketCommentsController::addComment($_POST);

    header('Location: ' . APP_URL . 'ticket?id=' . (int)$_POST['ticket_id']);
    exit;
  }
}
