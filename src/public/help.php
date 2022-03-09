<?php

require_once '../init.php';

User::authorize(['admin', 'advisor', 'reviewer']);

echo HTML::template('help.php', ['layout' => 'admin/_layout.php']);
