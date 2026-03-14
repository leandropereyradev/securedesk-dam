<?php

namespace app\models;

use app\core\Database;
use PDO;

class TicketCommentsModel
{
  public static function addComment(int $ticketId, int $userId, string $comment): ?int
  {
    $pdo = Database::getConnection();

    $ticketId = (int)$ticketId;
    $comment  = trim($comment);

    if ($ticketId <= 0 || $comment === '') {
      return false;
    }

    $sql = "
            INSERT INTO ticket_comments (ticket_id, user_id, comment)
            VALUES (:ticket_id, :user_id, :comment)
        ";

    $stmt = $pdo->prepare($sql);

    $success = $stmt->execute([
      ':ticket_id' => $ticketId,
      ':user_id'   => $userId,
      ':comment'   => $comment,
    ]);

    if (!$success) return null;

    return (int)$pdo->lastInsertId();
  }

  public static function getCommentsByTicket(int $ticketId): array
  {
    $pdo = Database::getConnection();

    $sql = "
            SELECT 
                tc.id,
                tc.comment,
                tc.created_at,
                u.username
            FROM ticket_comments tc
            INNER JOIN users u ON tc.user_id = u.id
            WHERE tc.ticket_id = :ticket_id
            ORDER BY tc.created_at DESC
        ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':ticket_id' => $ticketId]);

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
  }
}
