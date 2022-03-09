<?php

require_once '../../init.php';

User::authorize('admin');

$c = new ModelController(User::class);

$c->create();
$c->read();

if ($c->action() == 'update') {
    Form::assertCsrfToken();

    $user = $c->model();
    $user->email = HTTP::get('email');
    $user->isAdmin = HTTP::post('isAdmin', 0);
    $user->isAdvisor = HTTP::post('isAdvisor', 0);
    $user->isReviewer = HTTP::post('isReviewer', 0);

    $currentUser = User::current();

    if ($user->email == $currentUser->email && $currentUser->isAdmin) {
        $user->isAdmin = 1;
    }

    $user->save();
    HTTP::redirect('../admin/users.php');
}

$c->delete();

if ($c->done()) {
    // TODO: Show success/error message
    HTTP::redirect('../admin/users.php');
}

echo HTML::template('admin/user.php', ['form' => $c->form()]);
