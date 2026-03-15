<?php

namespace app\controllers;

class TicketReportsController
{
  public static function getReportHTML(int $ticketId): ?array
  {
    $ticket = TicketsController::getTicketById($ticketId);

    if (!$ticket) {
      return null;
    }

    $ticket['comments'] =
      TicketCommentsController::listAll($ticketId);

    $ticket['history'] =
      TicketHistoryController::listHistory($ticketId);

    $ticket['attachments'] =
      AttachmentsController::getAttachmentsByTicket($ticketId);

    return $ticket;
  }
}
