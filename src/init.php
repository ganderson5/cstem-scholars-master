<?php

session_start();

require_once __DIR__ . '/config.sample.php';
require_once __DIR__ . '/vendor/autoload.php';

DB::configure(DB_CONNECTION_STRING, DB_USERNAME, DB_PASSWORD, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
define('ROOT', __DIR__);
