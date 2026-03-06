<?php

namespace app\controllers;

use PDO;

class TicketCommentsController
{
  public static function addComment(PDO $pdo, array $data): bool
  {
    $ticketId = (int)($data['ticket_id'] ?? 0);
    $comment  = trim($data['comment'] ?? '');

    if ($ticketId <= 0 || $comment === '') {
      return false;
    }

    $sql = "
        INSERT INTO ticket_comments (ticket_id, user_id, comment)
        VALUES (:ticket_id, :user_id, :comment)
    ";

    $stmt = $pdo->prepare($sql);

    return $stmt->execute([
      ':ticket_id' => $ticketId,
      ':user_id'   => $_SESSION['user_id'],
      ':comment'   => $comment
    ]);
  }
}
