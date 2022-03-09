<?php

helper('crud');
helper('money');

$title = 'Periods';
$layout = 'admin/_layout.php';
?>

<h1>Application Periods</h1>

<?php
if ($form->errors()) {
    echo $form->errors();
}
?>

<table>
    <tr>
        <th>Start Date</th>
        <th>Student Deadline</th>
        <th>Review Deadline</th>
        <th>Total Budget</th>
        <th></th>
    </tr>

    <tr>
        <form method="POST">
            <?= $form->csrf() ?>
            <td><?= $form->date('beginDate', ['required']) ?></td>
            <td><?= $form->date('deadline', ['required']) ?></td>
            <td><?= $form->date('advisorDeadline', ['required']) ?></td>
            <td><?= $form->money('budget', ['required']) ?></td>
            <td>
                <button type="submit">Create</button>
            </td>
        </form>
    </tr>

    <?php
    foreach ($periods as $p) { ?>
        <tr>
            <td><?= date('M j, Y', strtotime($p->beginDate)) ?></td>
            <td><?= date('M j, Y', strtotime($p->deadline)) ?></td>
            <td><?= date('M j, Y', strtotime($p->advisorDeadline)) ?></td>
            <td><?= usd($p->budget) ?></td>
            <td class="button-group"><?= actionButtons('periods.php', $p->key()) ?></td>
        </tr>
        <?php
    } ?>
</table>
