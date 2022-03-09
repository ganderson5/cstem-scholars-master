<?php

$title = 'View application Status';
helper('application_status_label');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Application Status</title>
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="../CSS/students.css">
    <link rel="stylesheet" href="../CSS/app_status.css">
    <link rel="icon" href="../favicon.png"/>
</head>
<body>
<form>
    <div class="logout">
        <div class="button-section">
            <button type="submit" class="button" name="logout" formaction="../logout.php">Log Out</button>
        </div>
    </div>
</form>
<main class="form">
    <h1>Application: <?= e($application->title) ?><br></h1>
    <?= HTML::template('application_details.php', $application) ?>
</main>
</body>
</html>
