<?php

helper('application_status_label');
?>

<table>
    <thead>
    <th style="width: 20%">Student Name</th>
    <th style="width: 55%">Title</th>
    <th style="width: 25%">Status</th>
    </thead>

    <?php
    foreach ($applications as $a) { ?>
        <tr>
            <td><?= e($a->name) ?></td>
            <td><?= HTML::link("../admin/applications.php?id={$a->id}", e($a->title)) ?></td>
            <td><?= applicationStatus($a) ?></td>
        </tr>
    <?php
    } ?>
</table>
