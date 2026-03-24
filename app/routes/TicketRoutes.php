<?php

namespace app\routes;

use app\controllers\AttachmentsController;
use app\controllers\AuditLogsController;
use app\controllers\SessionController;
use app\controllers\TicketCommentsController;
use app\controllers\TicketHistoryController;
use app\controllers\TicketReportsController;
use app\controllers\TicketsController;
use app\controllers\UsersController;
use app\core\ViewContext;
use app\helpers\RedirectHelper;

class TicketRoutes
{
  public static function ticketsGet()
  {
    SessionController::requireLogin();

    $filters = [
      'status' => $_GET['status'] ?? null,
      'priority' => $_GET['priority'] ?? null,
      'assigned_to' => $_GET['assigned_to'] ?? null,
      'q' => $_GET['q'] ?? null,
      'search_in' => $_GET['search_in'] ?? 'all'
    ];

    $tickets = TicketsController::listAll($filters);
    $users = UsersController::listAll();

    if (!empty($filters['q'])) {

      $cleanFilters = array_filter($filters, function ($value) {
        return $value !== null && trim((string)$value) !== '';
      });

      $queryString = http_build_query($cleanFilters);

      AuditLogsController::logQuery(
        $_SESSION['user_id'],
        $queryString,
        'tickets'
      );
    }

    ViewContext::set('tickets', $tickets);
    ViewContext::set('users', $users);
    ViewContext::set('filters', $filters);
  }

  public static function ticketCreatePost()
  {
    $result = TicketsController::create();

    if (!$result['success']) {
      $_SESSION['ticket_error'] = $result['error'];
    }

    RedirectHelper::to('tickets');
    exit;
  }

  public static function ticketGet()
  {
    SessionController::requireLogin();

    $ticketId = (int)$_GET['id'];

    $ticket = TicketsController::getTicketById($ticketId);

    if ($ticket === null) {
      RedirectHelper::TicketError(
        'Ticket no encontrado. Haz sido redirigido a Lista de Tickets'
      );
    }

    $ticket['attachments'] = AttachmentsController::getAttachmentsByTicket($ticketId);
    $ticket['comments'] = TicketCommentsController::listAll($ticketId);
    $ticket['history'] = TicketHistoryController::listHistory($ticketId);

    ViewContext::set('ticket', $ticket);
  }

  public static function ticketEditGet()
  {
    SessionController::requireLogin();

    $ticketId = (int)$_GET['id'];
    $ticket = TicketsController::getTicketById($ticketId);

    $users = UsersController::listAll();

    ViewContext::set('users', $users);
    ViewContext::set('ticket', $ticket);
  }

  public static function ticketEditPost()
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

    RedirectHelper::to('ticket?id=' . $ticketId);
    exit;
  }

  public static function ticketReportGet()
  {
    SessionController::requireLogin();

    $ticketId = (int)$_GET['id'];

    $report = TicketReportsController::getReportData($ticketId);

    if ($report === null) {
      RedirectHelper::to('tickets');
      exit;
    }

    ViewContext::set('report', $report);

    AuditLogsController::logExport(
      $_SESSION['user_id'],
      'HTML',
      "Ticket ID $ticketId"
    );
  }

  public static function ticketsExportCsvGet()
  {
    SessionController::requireLogin();

    $filters = [
      'status' => $_GET['status'] ?? null,
      'priority' => $_GET['priority'] ?? null,
      'assigned_to' => $_GET['assigned_to'] ?? null,
      'q' => $_GET['q'] ?? null,
      'search_in' => $_GET['search_in'] ?? 'all'
    ];

    AuditLogsController::logExport(
      $_SESSION['user_id'],
      'CSV',
      'Lista de tickets'
    );

    TicketReportsController::exportCsv($filters);
  }

  public static function ticketExportPdfGet()
  {
    SessionController::requireLogin();

    $ticketId = (int)$_GET['id'];

    $report = TicketReportsController::getReportData($ticketId);

    if ($report === null) {
      RedirectHelper::to('tickets');
      exit;
    }

    AuditLogsController::logExport(
      $_SESSION['user_id'],
      'PDF',
      "Ticket ID $ticketId"
    );

    TicketReportsController::exportPdf($report);
  }
}
