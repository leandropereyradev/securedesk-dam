<?php

function initTicketHistoryDatabase(PDO $pdo): void
{
  $sql = "CREATE TABLE IF NOT EXISTS ticket_history (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            ticket_id INTEGER NOT NULL,
            user_id INTEGER NOT NULL,
            field TEXT NOT NULL,
            old_value TEXT,
            new_value TEXT,
            changed_at DATETIME DEFAULT CURRENT_TIMESTAMP,

            FOREIGN KEY(ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
            FOREIGN KEY(user_id) REFERENCES users(id)
        )";

  $pdo->exec($sql);
}
