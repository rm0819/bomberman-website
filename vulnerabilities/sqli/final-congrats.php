<?php
#written by Matt Smith

define( 'DVWA_WEB_PAGE_TO_ROOT', '../../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'admin' ) );

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

<div id=\"whoa\" class=\"container\" style=\"border: 5px solid var(--color-secondary);\">

	<h2 class=\"form__normal-text\">Whoa!</h2>

	<hr />
		
	<p class-\"form__normal-text\">
		Wow. You did it! That must've been hard. Nono, don't deny it.<br />
		Good job getting through that. That's all the website has to offer now, but puh-lease talk to Matt about adding more because he's out of ideas.<br />
		Click below for your finishing prize!
	</p>

	<br />

	<a title=\"Please, please click me\" class=\"form__button\" style=\"text-decoration:none;\" href=\"../../information/prize.txt\" download>Click me please!</a>
			
</div>

<div id=\"footer\">
	<script src='" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/js/congrats.js'></script>
</div>
";



?>