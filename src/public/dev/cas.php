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
            font-size: 12px;
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
        .container {
            position: static;
            margin: 0 auto;
            width: 330px;
        }
        footer {
            left: 0;
            bottom: 0;
            width: 100%; 
            background-color: black;
            color: white;
            text-align: center;
        }
        .footer-text {
            margin-bottom: 12px;
        }
        .container-footer {
            padding: 3px;
        }
    </style>
</head>
<body>

<div class="container">
<header>
        <img src="https://sso.ewu.edu/idp/images/dummylogo.png" alt="Replace or remove this logo">
</header>
<!-- <h1>User Login :)</h1> -->
<form method="POST">
    <label>Name <input type="text" name="name" value="<?= HTTP::cookie('cas_name') ?>" required></label>
    <label>Email <input type="email" name="email" value="<?= HTTP::cookie('cas_email') ?>" required></label>
    <button type="submit">Login</button>
    
    <!-- <a href="?invalid&service=<?= $url ?>">› Issue an invalid ticket</a> -->
    <br>
    <a href="https://ewu.edu/helpdesk" target="_blank">› Need Help?</a>
</form>

<p> By logging in, I understand and agree to the:</p><p style="font: size 16px;;" onclick="myFunction()"><b>EWU Systems and Server Login Banner Notice</b></p><p></p>
<div id="myDIV" style="display:none">
<p style="text-align: justify;">This computer system is the property of Eastern Washington University and is for authorized use only.  Use of this system must comply with all legal and policy restrictions including <a href="https://sites.ewu.edu/policies/policies-and-procedures/ewu-901-02-appropriate-use-of-university-resources/" target="_blank">EWU Policy 901-02, Appropriate Use of University Resources</a> and <a href="https://inside.ewu.edu/policies/policies-and-procedures/ewu-203-01-information-security/" target="_blank">EWU Policy 203-01, Information Security</a>.</p>
      <br> 
<p style="text-align: jusify;"> There is no expectation of privacy with regard to the use of University computer systems.  All email and other information contained within this system is owned by the university and may be monitored, accessed, or disclosed for audit or legitimate state operational or management purposes, and is subject to disclosure under the <a href="http://apps.leg.wa.gov/rcw/default.aspx?cite=42.56" target="_blank">Public Records Act</a></p>
</div>

<p>Notice: To protect the health and safety of campus, EWU encourages anyone who is sick to stay home.</p>
<p>Due to the current pandemic, anyone who has symptoms associated with COVID-19 must stay home and report their condition to <a href="http://ewu.edu/reportcovid" target="_blank">EWU's COVID-19 Response Team</a><a> to receive further direction.</a></p><a>

<p>Symptoms of COVID-19 include any one of the following symptoms:</p>
<ul>
<li>A fever (100.4° F or higher), or the sense of having a fever</li>
<li>New loss of taste or smell</li>
<li>Shortness of breath or difficulty breathing</li>
<li>Chills or repeated shaking with chills</li>
</ul>
<p>Or at least two of the following symptoms:</p>
<ul>
<li>Cough</li>
<li>Sore throat</li>
<li>Muscle pain (that may not have been caused by a specific activity such as physical exercise)</li>
<li>Headache</li>
<li>Fatigue</li>
<li>Diarrhea/vomiting/nausea</li>
</ul>

<p>By continuing to log in to the university network, you acknowledge that you understand these symptoms and the university's restrictions. By continuing to log in, you also agree that if you have or develop any of these symptoms and you cannot attribute the symptoms to another health condition, that you will not enter any EWU facilities until you contact the COVID-19 Response Team.</p>
</a></div><a>
<!--
<footer>
<div class="container-footer">
    <p class="footer-text">
    <a>@ Eastern Washington University   |   </a>
    <a href="mailto:helpdesk@ewu.edu" style="color:white">helpdesk@ewu.edu</a>
    </p>
</div>
</footer>
    -->

<footer>
<div class="container-footer">
    <p class="footer-text">
    <!-- <a>@ Eastern Washington University      |</a> -->
    <a href="mailto:helpdesk@ewu.edu" style="color:white">@ Eastern Washington University      |      helpdesk@ewu.edu</a>
    </p>
</div>
</footer>
</div> <!--end container div-->
</body>
</html>
 