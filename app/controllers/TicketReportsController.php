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
}
