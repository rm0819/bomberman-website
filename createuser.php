<?php
#written by Matt Smith

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array(  ) );

dvwaDatabaseConnect();

if( isset( $_POST[ 'createAccountButton' ] ) && dvwaIsLoggedIn()) {

    $user = $_POST[ 'newUsername' ];
	$user = stripslashes( $user );
	$user = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $user ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));

	$pass = $_POST[ 'newPassword' ];
    $verifyPass = $_POST[ 'verifyPassword' ];

    if($user == '' | $pass == '') {
        //Failed account creation
        dvwaMessagePush( "Failed! Username and/or password field cannot be empty." ); 
        dvwaRedirect( ' createuser.php' );
    }

    if($pass == $verifyPass) {
	    $pass = stripslashes( $pass );
	    $pass = ((isset($GLOBALS["___mysqli_ston"]) && is_object($GLOBALS["___mysqli_ston"])) ? mysqli_real_escape_string($GLOBALS["___mysqli_ston"],  $pass ) : ((trigger_error("[MySQLConverterToo] Fix the mysql_escape_string() call! This code does not work.", E_USER_ERROR)) ? "" : ""));
	    $pass = md5( $pass );

		
		// Get the last created user to find there user_id
        $lastCreatedUserQuery = ("SELECT * FROM `users` WHERE `users`.`last_created` = 1");
		// Update the last created user to no longer be the last created user
        $updateLastCreatedUserQuery = ("UPDATE `users` SET `last_created` = '0' WHERE `users`.`last_created` = 1");


        $lastCreatedUserResult = @mysqli_query($GLOBALS["___mysqli_ston"],  $lastCreatedUserQuery );
        $updateLastCreatedUserResult = @mysqli_query($GLOBALS["___mysqli_ston"],  $updateLastCreatedUserQuery );

        $lastCreatedUser = $lastCreatedUserResult->fetch_array();
        $lastuserID = ($lastCreatedUser['user_id'] + 1);



        $query = ("INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `user`, `password`, `avatar`, `last_login`, `failed_login`, `last_created`) VALUES ('$lastuserID', '$user', '$user', '$user', '$pass', NULL, current_timestamp(), 0, 1)");
        
        $result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query );
    }

    $query  = "SELECT * FROM `users` WHERE user='$user' AND password='$pass';";
	$result = @mysqli_query($GLOBALS["___mysqli_ston"],  $query ) or die( '<pre>' . ((is_object($GLOBALS["___mysqli_ston"])) ? mysqli_error($GLOBALS["___mysqli_ston"]) : (($___mysqli_res = mysqli_connect_error()) ? $___mysqli_res : false)) . '.<br />Try <a href="setup.php">installing again</a>.</pre>' );
	if( $result && mysqli_num_rows( $result ) == 1 ) {    // Account creation successful...
		dvwaMessagePush( "Success! Account: '{$user}' created!" );
	}
    else {
        //Failed account creation
        dvwaMessagePush( "Failed! Account: '{$user}' already exists" );   
    }
}
elseif (!dvwaIsLoggedIn()) {
	dvwaMessagePush("Cannot create an account until you are logged in. >:)");
}

$messagesHtml = messagesPopAllToHtml();

echo "<!DOCTYPE html>

<html lang=\"en-GB\">

	<head>

		<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />

		<title>Sign Up</title>

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

		<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"> <!-- Fonts -->
    	<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    	<link href=\"https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600&display=swap\" rel=\"stylesheet\">

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "testsite/src/maintest.css\" />

	</head>

	<body>

	<div id=\"wrapper\">

	<div id=\"header\">

	<br />

	<br />

	</div> <!--<div id=\"header\">-->

	<div class=\"container\" id=\"content\" style=\"border: 5px solid var(--color-secondary);\">

	<form class=\"form\" id=\"createAccount\" action=\"createuser.php\" method=\"post\" >
	<h1 class=\"form__title\">Sign-up</h1>

	<fieldset style=\"border: 2px solid var(--color-secondary);\">

			<label for=\"user\">Enter new username</label> <input type=\"text\" class=\"form__input\" autofocus size=\"20\" name=\"newUsername\"><br />


			<label for=\"pass\">Enter password</label> <input type=\"password\" class=\"form__input\" AUTOCOMPLETE=\"off\" size=\"20\" name=\"newPassword\"><br />


            <label for=\"verifyPass\">Verify password</label> <input type=\"password\" class=\"form__input\" AUTOCOMPLETE=\"off\" size=\"20\" name=\"verifyPassword\"><br />


			<p title=\"You don't already have an account? ,':|\" class=\"form__submit\"><input type=\"submit\" value=\"Create account\" name=\"createAccountButton\" ></p>

	</fieldset>

	</form>

	<a class=\"form__link-text\" id=\"createAccount\" href=\"login.php\">Already have an account? Log in</a>

	<br />
	<br />

    {$messagesHtml}

	<!-- <img src=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/images/RandomStorm.png\" /> -->
	</div > <!--<div id=\"content\">-->

	<div id=\"footer\">

		<!--FOOTER AREA-->

	</div> 
		<script src='" . DVWA_WEB_PAGE_TO_ROOT . "testsite/src/main.js'></script>
	<!--<div id=\"footer\"> -->

	</div>
	
	<!--<div id=\"wrapper\"> -->

	</body>

</html>";







?>