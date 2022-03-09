<?php

require_once '../../init.php';
User::authorize('student');
$period = Period::current();
?>
<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Thank you!</title>
    <link href='http://fonts.googleapis.com/css?family=Bitter' rel='stylesheet' type='text/css'>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <link rel="stylesheet" href="../CSS/students.css">
</head>

<body>

<form role="form" method="post" align="right">
    <div class="logout">
        <div class="button-section">
            <button type="submit" class="button" name="logout" formaction="../logout.php">Logout</button>
        </div>
    </div>
</form>

<div class="form">
    <h1>Thank you for your submission!</h1>
    <form>
        <div class="section"></div>
        <div class="inner-wrap">
            <label>Be sure to check your email and follow up with your advisor. Your application must be approved by
                your advisor before <strong><?= date("M j, Y", strtotime($period->advisorDeadline)) ?></strong>.</label>
        </div>
    </form>
</div>

</body>
</html>
