<?php

require '../../init.php';
DEBUG or exit('You are not authorized to access this page');

$url = filter_var(HTTP::get('service'), FILTER_VALIDATE_URL);
$url .= strstr($url, '?') ? '&' : '?';

if (HTTP::get('login') !== null) {
    if (HTTP::isPost()) {
        $name = HTTP::post('name');
        $email = HTTP::post('email');

        $expires = time() + 60 * 60 * 24 * 30; // 30 days
        setcookie('cas_name', $name, $expires);
        setcookie('cas_email', $email, $expires);

        HTTP::redirect("{$url}ticket=ST-$name::$email");
    }
} elseif (HTTP::get('serviceValidate') !== null) {
    $ticket = HTTP::get('ticket', null);

    if (!$ticket || $ticket == 'ST-invalid') {
        exit(
        "
            <cas:serviceResponse xmlns:cas=\"http://www.yale.edu/tp/cas\">
                <cas:authenticationFailure code=\"INVALID_TICKET\">
                    Ticket $ticket not recognized
                </cas:authenticationFailure>
            </cas:serviceResponse>
        "
        );
    }

    $ticket = preg_replace('/^ST-/', '', $ticket);
    [$name, $email] = explode('::', $ticket);
    [$firstName, $lastName] = explode(' ', $name . ' ');
    $username = explode('@', $email)[0];
    $id = '00' . str_pad(hexdec(crc32($email)) % 1000000, 6, '0', STR_PAD_LEFT);
    $userType = 'Student';

    exit(
    "
        <cas:serviceResponse xmlns:cas=\"http://www.yale.edu/tp/cas\">
            <cas:authenticationSuccess>
                <cas:user>$username</cas:user>
                <cas:attributes>
                    <cas:Ewuid>$id</cas:Ewuid>
                    <cas:UserType>$userType</cas:UserType>
                    <cas:Email>$email</cas:Email>
                    <cas:FirstName>$firstName</cas:FirstName>
                    <cas:LastName>$lastName</cas:LastName>
                </cas:attributes>
            </cas:authenticationSuccess>
        </cas:serviceResponse>
    "
    );
} elseif (HTTP::get('invalid') !== null) {
    HTTP::redirect("{$url}ticket=ST-invalid");
} else {
    exit('Invalid request');
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>DEV SSO</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 14px;
        }

        h1 {
            text-align: center;
        }

        form {
            width: 240px;
            margin: 0 auto;
        }

        label {
            font-weight: bold;
            text-transform: uppercase;
            display: block;
            margin-bottom: 16px;
        }

        input[type="text"], input[type="email"] {
            width: 100%;
            font-size: 14px;
            border: 1px solid #B7B7B7;
            border-radius: 4px;
            display: block;
            padding: 13px 12px;
            margin-top: 6px;
        }

        button {
            background: #A10022;
            color: #ffffff;
            box-sizing: content-box;
            width: 100%;
            cursor: pointer;
            padding: 13px 12px;
            border: none;
            font-size: 14px;
            border-radius: 4px;
            margin-bottom: 16px;
        }

        button:hover {
            background: #333333;
        }

        a:link, a:visited {
            font-size: 14px;
            text-decoration: none;
            color: #a10022;
            display: block;
        }
    </style>
</head>
<body>
<h1>Developer Login</h1>
<form method="POST">
    <label>Name <input type="text" name="name" value="<?= HTTP::cookie('cas_name') ?>" required></label>
    <label>Email <input type="email" name="email" value="<?= HTTP::cookie('cas_email') ?>" required></label>
    <button type="submit">Login</button>
    <a href="?invalid&service=<?= $url ?>">› Issue an invalid ticket</a>
</form>
</body>
</html>
