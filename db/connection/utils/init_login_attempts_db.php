<?php

function initLoginAttemptsDatabase(PDO $pdo): void
{
  $sql = "CREATE TABLE IF NOT EXISTS login_attempts (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            user_id INTEGER NOT NULL,
            ip_address TEXT,
            attempts INTEGER DEFAULT 0,
            blocked_until DATETIME NULL,
            last_attempt DATETIME NOT NULL,

            FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE SET NULL
        )";

  $pdo->exec($sql);
}
