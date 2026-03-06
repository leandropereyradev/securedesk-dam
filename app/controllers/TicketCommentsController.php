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

  public static function listAll(PDO $pdo, int $ticketId): array
  {

    $sql = "
        SELECT 
            tc.id,
            tc.comment,
            tc.created_at,
            u.username
        FROM ticket_comments tc
        INNER JOIN users u ON tc.user_id = u.id
        WHERE tc.ticket_id = :ticket_id
        ORDER BY tc.created_at ASC
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
      ':ticket_id' => $ticketId
    ]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
