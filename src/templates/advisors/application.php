<?php

$title = 'View application';
$layout = 'admin/_layout.php';
?>

<h1><?= e($application->title) ?></h1>

<?= HTML::template('application_details.php', $application) ?>

<br>

<ul class="tabs">
    <li><a class="active" href="#award">Accept</a></li>
    <li><a href="#reject">Reject</a></li>
</ul>

<div class="tab" id="award">
    <h2>Award Application</h2>

    <form method="POST">
        <?= $form->csrf() ?>

        <p>Comments will be appended to emails. These are optional.</p>

        <div class="form-group">
            <label for="studentComment">Message for student (optional):</label><br>
            <?= textarea('studentComment', HTTP::post('studentComment'), ['rows' => 10, 'style' => 'width: 100%']) ?>
        </div>

        <div class="form-group">
            <label for="reviewerComment">Comment for assigned reviewers (optional):</label><br>
            <?= textarea('reviewerComment', HTTP::post('reviewerComment'), ['rows' => 10, 'style' => 'width: 100%']) ?>
        </div>

        <button type="submit" name="buttonName" value="accept">Accept</button>
    </form>
</div>

<div class="tab" id="reject">
    <h2>Reject Application</h2>

    <form method="POST">
        <?= $form->csrf() ?>

        <div class="form-group">
            <label for="studentComment">Reason (required):</label><br>
            <?= textarea(
                'studentComment',
                HTTP::post('studentComment'),
                ['rows' => 10, 'style' => 'width: 100%', 'required']
            ) ?>
        </div>

        <button type="submit" name="buttonName" value="reject" class="danger">Reject</button>
    </form>
</div>
