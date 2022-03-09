<?php

helper('application_status_label');
helper('money');

$application = $v;
$sum = array_reduce($application->budgetTable(), fn($sum, $x) => $sum + $x->itemCost);
?>

<section class="app-details">
    <p><strong>Status:</strong> <?= applicationStatus($application) ?></p>

    <p>
        <strong>Student:</strong>
        <?= e($application->name) ?>
        <<?= HTML::link('mailto:' . urlencode($application->email), e($application->email)) ?>>
    </p>

    <p><strong>Student ID:</strong> <?= e($application->studentID()) ?></p>
    <p><strong>Major:</strong> <?= e($application->major) ?></p>
    <p><strong>GPA:</strong> <?= e($application->gpa) ?></p>
    <p><strong>Graduating:</strong> <?= date("M j, Y", strtotime($application->graduationDate)) ?></p>

    <p>
        <strong>Advisor:</strong>
        <?= e($application->advisorName) ?>
        <<?= HTML::link('mailto:' . urlencode($application->advisorEmail), e($application->advisorEmail)) ?>>
    </p>

    <p><strong>Project description:</strong></p>
    <pre><?= e($application->description) ?></pre>

    <p><strong>Project Timeline:</strong></p>
    <pre><?= e($application->timeline) ?></pre>

    <p><strong>Budget Plan:</strong></p>
    <pre><?= e($application->justification) ?></pre>

    <p><strong>Total budget amount:</strong> $<?= e($application->totalBudget) ?></p>
    <p><strong>Requested budget amount:</strong> $<?= e($application->requestedBudget) ?></p>

    <p><strong>Budget Table:</strong></p>
    <table id="budget-table">
        <tr>
            <th style="width: 30%">Item</th>
            <th style="width: 50%">Description</th>
            <th style="width: 15%">Cost</th>
        </tr>

        <?php
        foreach ($application->budgetTable() as $row) { ?>
            <tr>
                <td><?= e($row->item) ?></td>
                <td><?= e($row->itemDesc) ?></td>
                <td><?= usd($row->itemCost) ?></td>
            </tr>
            <?php
        } ?>

        <tr>
            <td colspan="2"></td>
            <td style="font-weight: bold"><?= usd($sum) ?></td>
        </tr>
    </table>
</section>
