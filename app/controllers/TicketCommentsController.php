<?php

namespace app\controllers;

use PDO;
use app\models\TicketCommentsModel;

class TicketCommentsController
{
  public static function addComment(array $data): bool
  {
    $ticketId = (int)($data['ticket_id'] ?? 0);
    $comment  = $data['comment'] ?? '';
    $userId   = $_SESSION['user_id'] ?? 0;

    return TicketCommentsModel::addComment($ticketId, $userId, $comment);
  }

  public static function listAll(int $ticketId): array
  {
    return TicketCommentsModel::getCommentsByTicket($ticketId);
  }
}
