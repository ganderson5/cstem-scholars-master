<?php

$layout = 'emails/layout.php';
?>

<p>Hello <?= e($application->name) ?>, </p>
<p>
    Your application: <?= e($application->title) ?> for <?= e($period->deadline) ?> has been forwarded for review.
    This does -not- mean it has been approved, it is still under review.
</p>
<?= $studentComment ?>
