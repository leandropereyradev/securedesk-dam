<?php
// Ruta absoluta al directorio base del proyecto
define('BASE_PATH', dirname(__DIR__));

// Configuración de la aplicación
define('APP_URL', 'http://localhost/securedesk-dam/');
define('APP_NAME', 'SecureDesk DAM');

// Rutas absolutas a las bases de datos SQLite
define('SECUREDESK_DB_PATH', BASE_PATH . '/db/securedesk.sqlite');
define('USERS_DB_PATH', BASE_PATH . '/db/users.sqlite');
define('TICKETS_DB_PATH', BASE_PATH . '/db/tickets.sqlite');
