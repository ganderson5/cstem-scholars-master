<?php

define('DEBUG', true);
define('BASE_URL', 'https://localhost');
define('ADMIN_EMAIL', 'admin@example.edu');

/* CAS Protocol Configuration */
 define('CAS_VERSION', "2.0");
 define('CAS_HOSTNAME', 'localhost');
 define('CAS_PORT', 443);
 define('CAS_URI', '/dev/cas.php?');
 define('CAS_CA_CERT', null);

/* Database Configuration */
define('DB_HOST', 'localhost');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', '');
define('DB_NAME', 'researchGrant');
define('DB_CONNECTION_STRING', 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME);

/* Mail Configuration */
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'ewundergraduateresearch@gmail.com');
define('SMTP_PASSWORD', 'rscddkwxosqelmcl');
define('SMTP_FROM_EMAIL', 'ewundergraduateresearch@gmail.com');
define('SMTP_FROM_NAME', 'EWU CSTEM Scholars');

date_default_timezone_set('America/Los_Angeles');
