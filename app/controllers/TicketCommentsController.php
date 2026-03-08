<?php

namespace app\controllers;

use app\models\TicketCommentsModel;

class TicketCommentsController
{
  public static function addComment(array $data): bool
  {
    $ticketId = (int)($data['ticket_id'] ?? 0);
    $comment  = $data['comment'] ?? '';
    $userId   = $_SESSION['user_id'] ?? 0;

    $commentId = TicketCommentsModel::addComment($ticketId, $userId, $comment);

    if ($commentId === null) {
      return false;
    }

    AuditLogsController::logCommentAdd(
      $ticketId,
      $commentId,
      $userId,
      $comment
    );

    return true;
  }

  public static function listAll(int $ticketId): array
  {
    return TicketCommentsModel::getCommentsByTicket($ticketId);
  }
}
