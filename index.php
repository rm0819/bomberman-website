<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array(  ) );

if(isset($_POST['Login'])) {
	dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'login.php' );
}

if( isset( $_POST[ 'create_db' ] ) ) {
	if( $DBMS == 'MySQL' ) {
		include_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/DBMS/MySQL.php';
	}
	elseif($DBMS == 'PGSQL') {
		// include_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/DBMS/PGSQL.php';
		dvwaMessagePush( 'PostgreSQL is not yet fully supported.' );
		dvwaPageReload();
	}
	else {
		dvwaMessagePush( 'ERROR: Invalid database selected. Please review the config file syntax.' );
		dvwaPageReload();
	}
}

$messagesHtml = messagesPopAllToHtml();

echo" <!DOCTYPE html>
<html>

	<head>
		<title>Welcome!</title>

		<link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

		<!-- Fonts -->
		<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"> 							
    	<link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    	<link href=\"https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600&display=swap\" rel=\"stylesheet\">

		<link rel=\"stylesheet\" type=\"text/css\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "testsite/src/introtest.css\" />
	</head>

	<div class=\"header container\" style=\"position: absolute; top: 0;\">
		<button style=\"width: 10%;\" class=\"form__button\" id=\"loginButton\">Login</button>
		<button style=\"width: 10%;\" class=\"form__button form--right\" id=\"createAccountButton\">Create Account</button>
	</div>
	
	<div class=\"container\" style=\"width: 1000px; max-width: 1000px; border: 5px solid var(--color-secondary);\">
		<h1 class=\"form__normal-text\">Welcome Intern!</h1>
		<hr />
		<h2 class=\"form__normal-text\">This is a realistic test of your website vulnerability discovery and exploitation skills. You will be tested.</h2>
		<h3 class=\"form__normal-text\">You can start by attempting to login. Good luck!</h3>
		<form class=\"form--hidden\" id=\"hiddenForm\" action=\"#\" method=\"post\">
			<text class\"form__normal-text\">Click me!</text>
			<input class=\"form__normal-text\" name=\"create_db\" id=\"create_db\" type=\"submit\" value=\"Create / Reset Database\">
		</form>
		{$messagesHtml}
	</div>

	<div>


	</div>


	<div id=\"footer\">
		<button style=\"width: 15%;\" class=\"form--bottom-left form__button-info\" title=\"I'm too lazy to make this do anything, so just message me (Matt) on teams\">Any techical or website design advice?</button>
		<button style=\"width: 15%;\" class=\"form--bottom-left-up form__button\" id=\"createDatabaseButton\">Setting up the wesbite for the first time?</button>
		<script src='" . DVWA_WEB_PAGE_TO_ROOT . "testsite/src/main.js'></script>
	</div>

</html>";

?>
