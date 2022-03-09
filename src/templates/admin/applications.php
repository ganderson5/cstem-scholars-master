<?php

$title = 'Applications';
$layout = 'admin/_layout.php';
$sum = array_reduce($applications, fn($sum, $a) => $sum + $a->amountAwarded);

helper('message_flash');
helper('application_status_label');
helper('money');
?>

<h1>Applications</h1>

<?= messageFlash() ?>

<section class="filter">
    <label for="periodID">Choose a period:</label>
    <form id="periods" method="get">
        <select name="periodID" id="periodID">
            <?php
            foreach (Period::all('1 ORDER BY beginDate DESC') as $p) {
                $attrs = ['value' => $p->id];

                if ($p->id == $selectedPeriodID) {
                    $attrs[] = 'selected';
                }

                echo tag(
                    'option',
                    date("M j, Y", strtotime($p->beginDate)) . ' - ' . date("M j, Y", strtotime($p->deadline)),
                    $attrs
                );
            }
            ?>
        </select>
        <input type="submit" value="Change Period">
    </form>
</section>

<table>
    <tr>
        <th>Student Name</th>
        <th>Title</th>
        <th>Status</th>
        <th>Award</th>
    </tr>

    <?php
    foreach ($applications as $a) { ?>
        <tr>
            <td><?= e($a->name) ?></td>
            <td><?= HTML::link("../admin/applications.php?id={$a->id}", e($a->title)) ?></td>
            <td><?= applicationStatus($a) ?></td>
            <td><?= $a->amountAwarded ? usd($a->amountAwarded) : '<span class="na">N/A</span>' ?></td>
        </tr>
    <?php
    } ?>

    <tr>
        <td colspan="3"></td>
        <td><strong><?= $sum ? usd($sum) : '' ?></strong></td>
    </tr>
</table>
