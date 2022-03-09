<?php

require_once '../../init.php';

User::authorize('admin');

$periodID = Period::mostRecent()->id ?? null;
$applications = [];
$applications['ready'] = Application::all('periodID = ? AND status = "reviewed"', $periodID);
$applications['unassigned'] = Application::query(
    '
        SELECT * FROM Application 
        WHERE periodID = :periodID 
        AND status = "pending_review"
        AND id NOT IN (SELECT applicationID FROM Review WHERE periodID = :periodID)
    ',
    ['periodID' => $periodID]
)->fetchAll();

echo HTML::template('admin/index.php', $applications);
