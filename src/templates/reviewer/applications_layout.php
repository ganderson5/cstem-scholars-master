<?php

helper('application_status_label');

$title = 'Applications';
$layout = 'admin/_layout.php';
?>

<h1>Applications</h1>

<table>
    <thead>
    <th>Student Name</th>
    <th>Title</th>
    <th>Advisor</th>
    <th>Status</th>
    </thead>

    <?php
    foreach ($reviews as $r) { ?>
        <?php
        $a = $r->application() ?>
        <tr>
            <td><?= e($a->name) ?></td>
            <td><?= HTML::link("../reviewers/applications.php?id={$r->id}", e($a->title)) ?></td>
            <td><?= e($a->advisorName) ?></td>
            <td><?= applicationStatus($a) ?></td>
        </tr>
    <?php
    } ?>
</table>