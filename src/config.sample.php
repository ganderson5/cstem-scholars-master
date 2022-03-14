<?php

define('DEBUG', true);
define('BASE_URL', 'http://localhost');
define('ADMIN_EMAIL', 'admin@example.edu');

/* CAS Protocol Configuration */
define('CAS_VERSION', "2.0");
define('CAS_HOSTNAME', 'localhost');
define('CAS_PORT', 443);
define('CAS_URI', '/dev/cas.php?');
define('CAS_CA_CERT', null);

/* Database Configuration */
define('DB_HOST', 'database');
define('DB_USERNAME', 'dbUser');
define('DB_PASSWORD', 'superSecret');
define('DB_NAME', 'researchGrant');
define('DB_CONNECTION_STRING', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME);

/* Mail Configuration */
define('SMTP_HOST', '');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_FROM_EMAIL', 'noreply@ewu.edu');
define('SMTP_FROM_NAME', 'EWU CSTEM Scholars');

date_default_timezone_set('America/Los_Angeles');
