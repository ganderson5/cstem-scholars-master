<?php

require_once '../../init.php';

User::authorize('admin');

$periods = Period::all('1 ORDER BY beginDate DESC');
$c = new ModelController(Period::class);

$c->index('admin/periods.php', ['periods' => $periods, 'form' => $c->form()]);
$c->create();

if ($c->error()) {
    echo HTML::template('admin/periods.php', ['periods' => $periods, 'form' => $c->form()->disableInlineErrors()]);
    exit();
}

$c->read();
$c->update();
$c->delete(); // TODO: Handle PDOException thrown when period contains applications

if ($c->done()) {
    // TODO: Show success/error message
    HTTP::redirect('../admin/periods.php');
}

echo HTML::template('admin/period.php', ['form' => $c->form()]);
