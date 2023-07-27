<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array(  ) );

dvwaDatabaseConnect();

if( isset( $_POST[ 'Login' ] ) ) {
	// Anti-CSRF 
	/*
	if (array_key_exists ("session_token", $_SESSION)) {
		$session_token = $_SESSION[ 'session_token' ];
	} else {
		$session_token = "";
	}

	checkToken( $_REQUEST[ 'user_token' ], $session_token, 'login.php' );
	*/

	$user = $_POST[ 'username' ];
	$user = stripslashes( $user );
	$user = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $user ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	$pass = $_POST[ 'password' ];
	$pass = stripslashes( $pass );
	$pass = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $pass ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	$pass = md5( $pass );

	$query = ("SELECT table_schema, table_name, create_time
				FROM information_schema.tables
				WHERE table_schema='{$_DVWA['db_database']}' AND table_name='users'
				LIMIT 1");
	$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query );
	if( mysqli_num_rows( $result ) != 1 ) {
		dvwaMessagePush( "First time using DVWA.<br />Need to run 'setup.php'." );
		dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'setup.php' );
	}

	$query  = "SELECT * FROM `users` WHERE user='$user' AND password='$pass';";
	$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '.<br />Try <a href="setup.php">installing again</a>.</pre>' );
	$resultArray = $result->fetch_array();
	
	if( $result && mysqli_num_rows( $result ) == 1 ) {    // Login Successful...
		if( $user == 'admin') {
			dvwaMessagePush( "You have logged in as '{$user}'" );
			dvwaLogin( $user );
			dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/sqli/final-congrats.php' );
		}
		else {
			dvwaMessagePush( "You have logged in as '{$user}'" );
			dvwaLogin( $user );
			dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/sqli/congrats.php' );
		}
	}
	else {
		$query  = "SELECT * FROM `users` WHERE user='$user';";
		$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query );
		if( $result && mysqli_num_rows( $result ) == 1 ) {
			dvwaMessagePush("Incorrect password for '{$user}'.");
		}
		else{
			
			dvwaMessagePush("Username '{$user}' does not exist.");
		}
	}



	// Login failed
	/*----------------This is only enabled for no-username enumeration-------------
	$thing  = rand(0,4);
	if($thing == 0)
		dvwaMessagePush( 'Nope' );
	elseif($thing == 1)
		dvwaMessagePush( 'Nuh uh' );
	elseif($thing == 2)
		dvwaMessagePush( 'Wrong' );
	elseif($thing == 3)
		dvwaMessagePush( 'Nah' );
	elseif($thing == 4)
		dvwaMessagePush( 'Negatory' );

	dvwaRedirect( 'login.php' );*/
}
$messagesHtml = messagesPopAllToHtml();

Header( 'Cache-Control: no-cache, must-revalidate');    // HTTP/1.1
Header( 'Content-Type: text/html;charset=utf-8' );      // TODO- proper XHTML headers...
Header( 'Expires: Tue, 23 Jun 2009 12:00:00 GMT' );     // Date in the past

// Anti-CSRF
generateSessionToken();


echo "<!DOCTYPE html>

<html lang=\"en-GB\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>Login</title>

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

		<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"> <!-- Fonts -->
    	<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    	<link href=\"https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600&display=swap\" rel=\"stylesheet\">

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "testsite/src/login.css\" />

	</head>

	<body>

	<div id=\"wrapper\">

		<div id=\"header\">

			<br />

			<br />

		</div> <!--<div id=\"header\">-->

		<div class=\"container\" id=\"content\" style=\"border: 5px solid var(--color-secondary);\">

			<form class=\"form\" id=\"login\" action=\"login.php\" method=\"post\" >

				<h1 class=\"form__title\">Login</h1>

				<fieldset style=\"border: 2px solid var(--color-secondary);\">

						<label for=\"user\">Username</label> <input type=\"text\" class=\"loginInput form__input\" autofocus size=\"20\" name=\"username\"><br />


						<label for=\"pass\">Password</label> <input type=\"password\" class=\"loginInput form__input\" AUTOCOMPLETE=\"off\" size=\"20\" name=\"password\"><br />


						<p title=\"Hello\" class=\"submit form__submit\"><input type=\"submit\" value=\"Continue\" name=\"Login\" ></p>

				</fieldset>

				" . tokenField() . "

			</form>

			<a class=\"form__link-text\" id=\"createAccountLink\" href=\"createuser.php\">Don't have an account? Create one</a>

			<br />
			<br />
			<br />
			<br />

			<button title=\"Click me for help\" id=\"moreInfoButton\" class=\"form__button\">More Info</button>


			{$messagesHtml}


			<!-- <img src=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/images/RandomStorm.png\" /> -->
		</div > <!--<div id=\"content\">-->

		<div id=\"footer\">

			<!--FOOTER AREA-->
			<script src='" . DVWA_WEB_PAGE_TO_ROOT . "testsite/src/enu.js'></script>

		</div><!--<div id=\"footer\"> -->

	</div><!--<div id=\"wrapper\"> -->

	</body>

</html>";


?>
