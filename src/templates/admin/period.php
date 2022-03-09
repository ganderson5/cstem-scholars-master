<?php

$title = 'Period Form';
$layout = 'admin/_layout.php';
?>

<form class="panel" method="POST">
    <h1>Period</h1>

    <?= $form->csrf() ?>

    <div class="form-group">
        <label for="beginDate">Start Date:</label>
        <?= $form->date('beginDate', ['required']) ?>
    </div>

    <div class="form-group">
        <label for="deadline">Student Deadline:</label>
        <?= $form->date('deadline', ['required']) ?>
    </div>

    <div class="form-group">
        <label for="advisorDeadline">Review Deadline:</label>
        <?= $form->date('advisorDeadline', ['required']) ?>
    </div>

    <div class="form-group">
        <label for="budget">Total Budget:</label>
        <?= $form->money('budget', ['required']) ?>
    </div>

    <button type="submit">Submit</button>
</form>
