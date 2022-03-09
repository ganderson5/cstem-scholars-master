<?php

$title = 'Administrator Dashboard';
$layout = 'admin/_layout.php';
$allSet = true;
?>

<h1>Administrator Dashboard</h1>

<?php
if (!User::exists('isAdvisor = 1')) { ?>
    <?php
    $allSet = false; ?>
    <p class="message error">
        There are currently no users with <strong>Advisor</strong> role. Students will not be able to submit their
        application without a valid advisor email that exists in the system. Add advisor users in
        <a href="users.php">Users</a> section.
    </p>
<?php
} ?>

<?php
if (!User::exists('isReviewer = 1')) { ?>
    <?php
    $allSet = false; ?>
    <p class="message warning">
        There are currently no users with <strong>Reviewer</strong> role. Applications accepted at this point will not
        have any reviewers automatically assigned. Add reviewers in <a href="users.php">Users</a> section.
    </p>
<?php
} ?>

<?php
if (!Period::current()) { ?>
    <?php
    $allSet = false; ?>
    <p class="message warning">
        No applications are currently being accepted. Manage application periods in <a href="periods.php">Periods</a>
        section.
    </p>
<?php
} ?>

<?php
if ($ready) { ?>
    <?php
    $allSet = false; ?>
    <h2>Applications Ready for Your Final Decision</h2>
    <?= template('applications_table.php', ['applications' => $ready]) ?>
<?php
} ?>

<?php
if ($unassigned) { ?>
    <?php
    $allSet = false; ?>
    <h2>Applications Without Assigned Reviewers</h2>
    <?= template('applications_table.php', ['applications' => $unassigned]) ?>
<?php
} ?>

<?php
if ($allSet) { ?>
    <p>You're all set! An application period is currently open and accepting new applications.</p>
<?php
} ?>

<?php

if (!User::exists('isAdvisor = 1 OR isReviewer = 1') && !Period::current()) {
    echo template('help.php');
}
?>
