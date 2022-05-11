<?php

helper('application_status_label');
?>

<table>
    <thead>
    <th style="width: 30%">Student Name</th>
    <th style="width: 40%">Title</th>
    <th style="width: 20%">Score</th>
    <th style="width: 20%">Status</th>

    </thead>

    <?php
    foreach ($applications as $a) { 

        $totalPoints= 0;
        $totalReviews=0;
        foreach ($a->reviews() as $review) { 
            $reviewer = $review->reviewer();
            $totalReviews++;
            foreach (Review::QUESTIONS as $i => $q) {
                $totalPoints += $review->{'q' . ($i + 1)};
            } 
        }
        if($totalReviews>0){ 
            $scorePercentage = (int)($totalPoints / ($totalReviews * 18) * 100);
        } else {
            $scorePercentage = 0;
        }
        ?>
        <tr>
            <td><?= e($a->name) ?></td>
            <td><?= HTML::link("../admin/applications.php?id={$a->id}", e($a->title)) ?></td>
            <td><?= $scorePercentage ?>%</td>
            <td><?= applicationStatus($a) ?></td>
        </tr>
    <?php
    } ?>
</table>
