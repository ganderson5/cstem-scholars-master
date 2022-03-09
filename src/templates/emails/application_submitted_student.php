<?php

$layout = 'emails/layout.php';
?>

<p>Hello <?= e($application->name) ?>, </p>
<p>Your CSTEM Scholars application was successfully submitted. You may go back and make changes to your application at
    any time before the <?= date("M j, Y", strtotime($period->deadline)) ?> deadline.</p>
