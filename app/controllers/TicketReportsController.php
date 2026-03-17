<?php

namespace app\controllers;

use app\helpers\DateHelper;

class TicketReportsController
{
  public static function getReportData(int $ticketId): ?array
  {
    $ticket = TicketsController::getTicketById($ticketId);

    if (!$ticket) {
      return null;
    }

    $comments =   TicketCommentsController::listAll($ticketId);

    $history =  TicketHistoryController::listHistory($ticketId);

    $attachments = AttachmentsController::getAttachmentsByTicket($ticketId);

    $report = [
      'generated_by' => $_SESSION['username'],
      'generated_at' => DateHelper::utcToMadrid(gmdate('Y-m-d H:i')),
      'ticket' => $ticket,
      'comments' => $comments,
      'history' => $history,
      'attachments' => $attachments
    ];

    return $report;
  }

  public static function exportCsv(): void
  {
    $filters = $_SESSION['tickets_filters'] ?? [
      'status' => null,
      'priority' => null
    ];

    $tickets = TicketsController::listAll($filters);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename="tickets.csv"');

    $file = fopen('php://output', 'w');

    fputcsv($file, [
      'id',
      'title',
      'status',
      'priority',
      'assigned_to',
      'created_at'
    ]);

    foreach ($tickets as $ticket) {

      fputcsv($file, [
        $ticket['id'],
        $ticket['title'],
        $ticket['status'],
        $ticket['priority'],
        $ticket['assigned_to_username'] ?? 'Sin asignar',
        $ticket['created_at']
      ]);
    }

    fclose($file);
    exit;
  }
}
