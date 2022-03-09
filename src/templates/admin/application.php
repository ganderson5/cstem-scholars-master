<?php

helper('crud');

$title = 'View application';
$layout = 'admin/_layout.php';
?>

<h1><?= e($application->title) ?></h1>

<?= $error ? tag('div', $error, ['class' => 'message error']) : '' ?>

<ul class="tabs">
    <li><a class="active" href="#award">Award</a></li>
    <li><a href="#reject">Reject</a></li>
    <li><a class="delete-tab" href="#delete">Delete</a></li>
</ul>

<div class="tab" id="award">
    <h2>Award Application</h2>

    <form method="POST">
        <?= input('hidden', 'csrfToken', Form::csrfToken()) ?>

        <div class="form-group">
            <label for="message">Message (optional):</label><br>
            <?= textarea('message', HTTP::post('message'), ['rows' => 10, 'style' => 'width: 100%']) ?>
        </div>

        <div class="form-group">
            <label for="amount">Amount awarded:</label><br>
            <?= input('number', 'amount', HTTP::post('amount'), ['min' => 0, 'step' => 0.01, 'required']) ?>
        </div>

        <button type="submit" name="action" value="award">Award</button>
    </form>
</div>

<div class="tab" id="reject">
    <h2>Reject Application</h2>

    <form method="POST">
        <?= input('hidden', 'csrfToken', Form::csrfToken()) ?>

        <div class="form-group">
            <label for="reason">Reason (required):</label><br>
            <?= textarea('reason', HTTP::post('reason'), ['rows' => 10, 'style' => 'width: 100%', 'required']) ?>
        </div>

        <button type="submit" name="action" value="reject" class="danger">Reject</button>
    </form>
</div>

<div class="tab" id="delete">
    <h2>Delete Application</h2>

    <p>
        This will <strong>permanently delete</strong> the application. The student associated with this application will
        <strong>not</strong> be notified. They will be able to fill out a new application if the application period
        is still open.
    </p>

    <?= deleteButton('', $application->key()) ?>
</div>

<h2>Application Details</h2>

<?= HTML::template('application_details.php', $application) ?>

<h2>Reviews</h2>

<?php
foreach ($application->reviews() as $review) { ?>
    <?php
    $reviewer = $review->reviewer() ?>

    <p>
        <strong><?= e($reviewer->name) ?></strong>
        <<?= HTML::link('mailto:' . urlencode($reviewer->email), e($reviewer->email)) ?>>:
    </p>
    <section class="review">

        <?php
        if (!$review->submitted) {
            echo tag('p', 'This review is not yet submitted');
        } else {
            ?>

            <?php
            foreach (Review::QUESTIONS as $i => $q) { ?>
                <p><?= $q ?></p>
                <blockquote><?= e($review->{'q' . ($i + 1)}) ?> / 3</blockquote>
                <?php
            } ?>

            <p>Comments:</p>
            <blockquote>
                <pre><?= $review->comments ? e($review->comments) : 'No comment' ?></pre>
            </blockquote>

            <p>Recommend funding?</p>
            <blockquote><?= $review->fundingRecommended ? 'Yes' : 'No' ?></blockquote>

            <?php
        } ?>

    </section>

    <?php
} ?>
