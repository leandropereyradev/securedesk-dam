<?php
// Cargar configuración y autoload
require_once __DIR__ . '/config.php';

// Conexiones y utilidades de la base de datos
require_once BASE_PATH . '/db/connection/connection.php';
require_once BASE_PATH . '/db/connection/utils/createInitialUsersIfEmpty.php';
require_once BASE_PATH . '/db/connection/utils/init_securedesk_db.php';
require_once BASE_PATH . '/db/connection/utils/init_users_db.php';
require_once BASE_PATH . '/db/connection/utils/init_tickets_db.php';
