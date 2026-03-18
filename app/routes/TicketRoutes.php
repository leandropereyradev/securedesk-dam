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
use app\helpers\RedirectHelper;

class TicketRoutes
{
  public static function ticketsGet()
  {
    SessionController::requireLogin();

    $tickets = TicketsController::listAll();

    $_SESSION['tickets'] = $tickets;
  }

  // Lista los tickets filtrados
  public static function ticketsPost()
  {
    SessionController::requireLogin();

    $filters = [
      'status' => $_POST['status'] ?? null,
      'priority'  => $_POST['priority'] ?? null
    ];

    $_SESSION['tickets_filters'] = $filters;

    $tickets = TicketsController::listAll($filters);

    $_SESSION['tickets'] = $tickets ?? [];

    RedirectHelper::to('tickets');
    exit;
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

    if (!$ticket) {
      RedirectHelper::to('tickets');
      exit;
    }

    $ticket['attachments'] = AttachmentsController::getAttachmentsByTicket($ticketId);

    $ticket['comments'] = TicketCommentsController::listAll($ticketId);

    $ticket['history'] = TicketHistoryController::listHistory(
      $ticketId
    );

    $_SESSION['ticket'] = $ticket;
  }

  public static function ticketEditGet()
  {
    SessionController::requireLogin();

    $_SESSION['users'] = UsersController::listAll();
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
    }

    $_SESSION['report'] = $report;

    AuditLogsController::logExport(
      $_SESSION['user_id'],
      'HTML',
      "Ticket ID $ticketId"
    );
  }

  public static function ticketsExportCsvGet()
  {
    SessionController::requireLogin();

    AuditLogsController::logExport(
      $_SESSION['user_id'],
      'CSV',
      'Lista de tickets'
    );

    TicketReportsController::exportCsv();
  }

  public static function ticketExportPdfGet()
  {
    SessionController::requireLogin();

    $ticketId = (int)$_GET['id'];

    $report = TicketReportsController::getReportData($ticketId);

    if ($report === null) {
      RedirectHelper::to('tickets');
    }

    AuditLogsController::logExport(
      $_SESSION['user_id'],
      'PDF',
      "Ticket ID $ticketId"
    );

    TicketReportsController::exportPdf($report);
  }
}
