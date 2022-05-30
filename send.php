<?php
    ini_set( 'display_errors', 1 );
    error_reporting( E_ALL );
    $to = "anderson.gpb@gmail.com";
    $subject = "PHP Mail Test script test!!!!";
    $message = "This is a test to check the PHP Mail functionality, I really hope this works this time!.";
    mail($to,$subject,$message);
    echo "Test email sent\n.";
?>
