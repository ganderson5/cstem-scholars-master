<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= $title ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        <?php include __DIR__ . '/../public/CSS/error_page.css' ?>
    </style>
</head>
<body>

<!-- Navbar (sit on top) -->

<!-- First Parallax Image with Logo Text -->
<header class="w3-display-container w3-content w3-center" style="max-width:1500px">
    <div class="bgimg-1 w3-display-container" id="home">
        <div class="w3-display-middle" style="white-space:nowrap;">
                <span class="w3-center w3-padding-large w3-black w3-xlarge w3-wide w3-animate-opacity">
                    CSTEM
                    <span class="w3-hide-small">
                        UNDERGRADUATE RESEARCH
                    </span>
                    GRANT
                </span>
        </div>
    </div>
</header>
<!-- Container (About Section) -->
<div class="w3-content w3-container w3-padding-32" id="about">
    <h3 class="w3-center"><?= $body ?></h3>
    <p></p>
</div>

<div class="w3-content w3-container w3-padding-8" id="apply">
</div>
<div style="text-align: center;">
    <a href="../../index.php" class="w3-button w3-grey w3-round w3-large" id="student">Back To Home Page</a>
    <br><br>
</div>
<!-- Footer -->
<footer class="foot w3-center w3-dark-grey w3-padding-32">
    <div class="social-media">
        <a href="https://www.facebook.com/ewueagles/">
            <i class="fa fa-facebook" style="font-size: 1.80em"></i>
        </a>
        <a href="https://twitter.com/ewueagles">
            <i class="fa fa-twitter" style="font-size: 1.80em"></i>
        </a>
        <a href="https://www.instagram.com/easternwashingtonuniversity/">
            <i class="fa fa-instagram" style="font-size: 1.7em"></i>
        </a>
        <a href="https://www.youtube.com/user/ewuvideo">
            <i class="fa fa-youtube-play" style="font-size: 1.6em"></i>
        </a>
        <a href="https://www.linkedin.com/school/eastern-washington-university/">
            <i class="fa fa-linkedin" style="font-size: 1.7em"></i>
        </a>
    </div>
    <div class="row-links">
        <div class="logos">
            <a class="logo" href="https://www.ewu.edu">
                <img src="../../images/footer-logo.png" alt="Eastern Washington University">
            </a>
            <br>
            <ul class="contacts">
                <li class="a">
                    <a href="tel:15093596200" style="text-decoration:none;">509.359.6200</a>
                </li>
                •
                <li class="b">
                    <a href="https://www.ewu.edu/contact-ewu" style="text-decoration:none;">Contact Information</a>
                </li>
            </ul>
            <br>
        </div>

        <ul class="left" style="list-style-type:none">
            <li class="a">
                <a href="https://www.ewu.edu/about/" style="text-decoration: none;">About EWU </a>
            </li>
            <li class="b">
                <a href="https://www.ewu.edu/apply/visit-ewu/" style="text-decoration: none;">Visit EWU</a>
            </li>
            <li class="c">
                <a href="https://www.ewu.edu/academics/#section-5" style="text-decoration: none;">Campus Locations</a>
            </li>
        </ul>
        <ul class="middle" style="list-style-type:none">
            <li class="a">
                <a href="https://sites.ewu.edu/foundation/" style="text-decoration: none;">EWU Foundation</a>
            </li>
            <li class="b">
                <a href="https://sites.ewu.edu/diversityandinclusion/" style="text-decoration: none;">Diversity</a>
            </li>
            <li class="c">
                <a href="https://sites.ewu.edu/hr/apply-for-jobs/" style="text-decoration: none;">Jobs</a>
            </li>
        </ul>
        <ul class="right" style="list-style-type:none">
            <li class="a">
                <a href="https://www.ewu.edu/about/leadership/" style="text-decoration: none;">Administration</a>
            </li>
            <li class="b">
                <a href="https://www2.ewu.edu/library" style="text-decoration: none;">EWU Libraries</a>
            </li>
            <li class="c">
                <a href="https://www2.ewu.edu/site-map?filter=a" style="text-decoration: none;">Site Map</a>
            </li>
        </ul>
        <ul class="farright" style="list-style-type:none">
            <li class="a">
                <a href="https://sites.ewu.edu/instructional-technology/learning-management-system"
                   style="text-decoration: none;">
                    Canvas</a>
            </li>
            <li class="b">
                <a href="https://eaglenet.ewu.edu/" style="text-decoration: none;">EagleNET</a>
            </li>
            <li class="c">
                <a href="index.php?id=admin" style="text-decoration: none;">Admin Login</a>
            </li>
        </ul>
    </div>
    <br>
    <ul class="facts">
        <li class="list-inline-item text-muted">&copy; 2018 Eastern Washington University</li>
        |
        <li class="priv">
            <a href="https://www.ewu.edu/privacy-policy" style="text-decoration: none;">Privacy Policy</a>
        </li>
        |
        <li class="access">
            <a href="https://www.ewu.edu/accessibility" style="text-decoration: none;">Accessibility</a>
        </li>
        |
        <li class="rules">
            <a href="https://sites.ewu.edu/policies/" style="text-decoration: none;">Rules and Policies</a>
        </li>
    </ul>
</footer>
</body>
</html>
