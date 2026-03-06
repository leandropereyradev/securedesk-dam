<?php

function initAttachmentsDatabase(PDO $pdo): void
{
  $sql = "
    CREATE TABLE IF NOT EXISTS attachments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        ticket_id INTEGER NOT NULL,
        filename TEXT NOT NULL,
        stored_name TEXT NOT NULL,
        size INTEGER NOT NULL,
        uploaded_by INTEGER NOT NULL,
        uploaded_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        
        FOREIGN KEY(ticket_id) REFERENCES tickets(id) ON DELETE CASCADE,
        FOREIGN KEY(uploaded_by) REFERENCES users(id)
    )";

  $pdo->exec($sql);
}
