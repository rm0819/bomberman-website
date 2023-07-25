<?php
#written by Matt Smith

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';


echo"
<!DOCTYPE html>
<head>

    <title>User Enumeration Info</title>

    <link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

    <!-- Fonts -->
	<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"> 							
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    <link href=\"https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600&display=swap\" rel=\"stylesheet\">

    <link rel=\"stylesheet\" type=\"text/css\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "testsite/src/sqltest.css\" />

</head>

<div id=\"congrats\" class=\"container\" style=\"border: 5px solid var(--color-secondary);\">

	<h2 class=\"form__normal-text\">Congrats!</h2>

	<hr />
		
	<p class-\"form__normal-text\">
		Good job! You did great getting this far. I'm kinda surprised you're here.... tbh... <br>Well, 
		if you want to keep going, I would try getting the password to the admin account. (Hint: the query function on the next page isn't very tolerant of injections)
		<br> Click the button below to continue!
	</p>

	<button title=\"Please click me\" id=\"continueToSQL\" class=\"form__button\" style=\"width:25%;\">Click me!</button>
			
</div>

<div id=\"footer\">
	<script src='" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/js/congrats.js'></script>
</div>
";



?>