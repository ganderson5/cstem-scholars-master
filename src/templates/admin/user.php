<?php

$title = 'User Form';
$layout = 'admin/_layout.php';
?>

<form class="panel" method="POST">
    <h1>User</h1>

    <?= $form->csrf() ?>

    <div class="form-group">
        <label for="name">Name:</label>
        <?= $form->text('name', ['required']) ?>
    </div>

    <div class="form-group">
        <label for="email">Email:</label>
        <?= $form->email('email', ['required']) ?>
    </div>

    <div class="form-group">
        <p>Roles:</p>
        <label><?= $form->checkbox('isAdvisor') ?> Advisor</label><br>
        <label><?= $form->checkbox('isReviewer') ?> Reviewer</label><br>
        <label><?= $form->checkbox('isAdmin') ?> Administrator</label><br>
    </div>

    <button type="submit">Submit</button>
</form>
