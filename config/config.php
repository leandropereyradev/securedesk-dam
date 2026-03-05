<?php
// Ruta absoluta al directorio base del proyecto
define('BASE_PATH', dirname(__DIR__));

// Configuración de la aplicación
define('APP_URL', 'http://localhost/securedesk-dam/');
define('APP_NAME', 'SecureDesk DAM');

// Rutas absolutas a la base de datos SQLite
define('SECUREDESK_DB_PATH', BASE_PATH . '/db/securedesk.sqlite');
// Tablas de la base de datos
define('USERS_DB_PATH', SECUREDESK_DB_PATH);
define('TICKETS_DB_PATH', SECUREDESK_DB_PATH);
define('ATTACHMENTS_DB_PATH', SECUREDESK_DB_PATH);
