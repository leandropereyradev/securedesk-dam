<?php

// Archivo de arranque del proyecto: carga la configuración
// y los ficheros necesarios para la conexión e inicialización
// de las bases de datos
require_once __DIR__ . '/config.php';

require_once __DIR__ . '/db/connection/connection.php';
require_once __DIR__ . '/db/connection/utils/init_securedesk_db.php';
require_once __DIR__ . '/db/connection/utils/init_users_db.php';
require_once __DIR__ . '/db/connection/utils/init_tickets_db.php';
