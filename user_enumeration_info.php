<?php
#written by Matt Smith

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';


echo" <!DOCTYPE html>
<head>

    <title>User Enumeration Info</title>

    <link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

    <!-- Fonts -->
	<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"> 							
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    <link href=\"https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600&display=swap\" rel=\"stylesheet\">

    <link rel=\"stylesheet\" type=\"text/css\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "testsite/src/maintest.css\" />

</head>

<div class=\"container\">

    <h2 class=\"form__normal-text\">User Enumeration</h2>

    <hr />

    <p class-\"form__normal-text\">
        User enumeration is a strategy that threat actors use to gain information about a database. It is the process of trying out tons of different usernames to see which are 
        valid in the database.
        Threat actors will use the information that the database returns when there is an attempt to login. For example, when you type a username that doesn't exist in the database, 
        some websites will return:<br><p class=\"form__message\">\"Error: username does not exist.\"</p>instead of returning:<br>
        <p class=\"form__message\">\"Incorrect username/password.\"</p>This allows threat actors to learn what usernames 
        are available for attacking. Instead of brute forcing username and password combinations, they only have to brute force the passwords because they know the username is valid.
        <br><br>
        I've attached a list of common usernames and passwords below that might be helpful while logging in ;)
    </p>

    <hr />

    <li><a title=\"It's safe I promise... >:)\" href=\"information/usernames.txt\" download>usernames.txt</a></li>

    <li><a href=\"information/passwords.txt\" download>passwords.txt</a></li>

    <li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/Enumeration' ) . "</li>


</div>";





?>