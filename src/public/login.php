<?php

$is_login = true;
require_once __DIR__ . "/../init.php";
require_once __DIR__ . "/../vendor/apereo/phpcas/CAS.php";

// Production code block. uses CAS
phpCAS::client(CAS_VERSION, CAS_HOSTNAME, CAS_PORT, CAS_URI);

if (DEBUG) {
    phpCAS::setDebug();
    phpCAS::setVerbose(true);
}

if (empty(CAS_CA_CERT)) {
    phpCAS::setNoCasServerValidation();
} else {
    phpCAS::setCasServerCACert(CAS_CA_CERT);
}

phpCAS::forceAuthentication();
$user = User::current();

if (!$user) {
    $username = phpCAS::getUser();
    $attributes = phpCAS::getAttributes();

    $id = $attributes['Ewuid'];
    $name = "{$attributes['FirstName']} {$attributes['LastName']}";
    $email = $attributes['Email'];

    $user = User::login($id, $name, $email);
}

if ($user->isStudent()) {
    HTTP::redirect('students/');
}

if ($user->isAdmin()) {
    HTTP::redirect('admin/');
}

if ($user->isAdvisor()) {
    HTTP::redirect('advisors/applications.php');
}

if ($user->isReviewer()) {
    HTTP::redirect('reviewers/applications.php');
}

throw new RuntimeException('Unreachable');
