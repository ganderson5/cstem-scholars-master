<?php

helper('money');
$layout = 'emails/layout.php';
?>

<p>Hello <?= e($application->name) ?>, </p>
<p>
    Your application, <?= e($application->title) ?>, was awarded <?= usd($application->amountAwarded) ?>.
</p>
<pre><?= $message ?></pre>
