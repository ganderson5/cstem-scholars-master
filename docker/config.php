<?php

define('DEBUG', false);
define('BASE_URL', 'http://localhost');
define('ADMIN_EMAIL', 'jnix1@eagles.ewu.edu');

if (isset($is_login)) {
    require_once __DIR__ . "/vendor/apereo/phpcas/CAS.php";
    /* CAS Protocol Configuration */
    define('CAS_VERSION', SAML_VERSION_1_1);
    define('CAS_HOSTNAME', 'login.ewu.edu');
    define('CAS_PORT', 443);
    define('CAS_URI', '/cas');
    define('CAS_CA_CERT', '/etc/ssl/certs/ca-certificates.crt');
}

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
