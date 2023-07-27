<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';

dvwaPageStartup( array( 'authenticated') );

$page = dvwaPageNewGrab();
$page[ 'title' ]   = 'DVWA Security' . $page[ 'title_separator' ].$page[ 'title' ];
$page[ 'page_id' ] = 'security';

$securityHtml = '';
if( isset( $_POST['seclev_submit'] ) ) {
	// Anti-CSRF
	checkToken( $_REQUEST[ 'user_token' ], $_SESSION[ 'session_token' ], 'security.php' );

	$securityLevel = '';
	switch( $_POST[ 'security' ] ) {
		case 'low':
			$securityLevel = 'low';
			break;
		case 'medium':
			$securityLevel = 'medium';
			break;
		case 'high':
			$securityLevel = 'high';
			break;
		default:
			$securityLevel = 'impossible';
			break;
	}

	dvwaSecurityLevelSet( $securityLevel );
	dvwaMessagePush( "Security level set to {$securityLevel}" );
	dvwaRedirect( DVWA_WEB_PAGE_TO_ROOT . 'vulnerabilities/sqli/index.php' );
}

$securityOptionsHtml = '';
$securityLevelHtml   = '';
foreach( array( 'low', 'medium', 'high', 'impossible' ) as $securityLevel ) {
	$selected = '';
	if( $securityLevel == dvwaSecurityLevelGet() ) {
		$selected = ' selected="selected"';
		$securityLevelHtml = "<p>Security level is currently: <em>$securityLevel</em>.<p>";
	}
	$securityOptionsHtml .= "<option value=\"{$securityLevel}\"{$selected}>" . ucfirst($securityLevel) . "</option>";
}

// Anti-CSRF
generateSessionToken();

$messagesHtml = messagesPopAllToHtml();

echo"
<head>

    <title>User Enumeration Info</title>

    <link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

    <!-- Fonts -->
	<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"> 							
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    <link href=\"https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600&display=swap\" rel=\"stylesheet\">

    <link rel=\"stylesheet\" type=\"text/css\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "testsite/src/security.css\" />

</head>

<div class=\"container\" style=\"border:5px solid var(--color-secondary);\">
	<h1>Security <img src=\"" . DVWA_WEB_PAGE_TO_ROOT . "dvwa/images/lock.png\" /></h1>

	<h2>Security Level</h2>

	{$securityHtml}

	<form action=\"#\" method=\"POST\">
		{$securityLevelHtml}
		<p>You can set the security level to low, medium, high or impossible. The security level changes the vulnerability level of the website:</p>
		<ol>
			<li> Low - This security level is completely vulnerable and <em>has no security measures at all</em>. It's use is to be as an example of how web application vulnerabilities manifest through bad coding practices and to serve as a platform to teach or learn basic exploitation techniques.</li>
			<li> Medium - This setting is mainly to give an example to the user of <em>bad security practices</em>, where the developer has tried but failed to secure an application. It also acts as a challenge to users to refine their exploitation techniques.</li>
			<li> High - This option is an extension to the medium difficulty, with a mixture of <em>harder or alternative bad practices</em> to attempt to secure the code. The vulnerability may not allow the same extent of the exploitation, similar in various Capture The Flags (CTFs) competitions.</li>
			<li> Impossible - This level should be <em>secure against all vulnerabilities</em>. It is used to compare the vulnerable source code to the secure source code. (It's literally not possible to attack)<br /></li>
		</ol>
		<select name=\"security\">
			{$securityOptionsHtml}
		</select>
		<input type=\"submit\" value=\"Submit\" name=\"seclev_submit\">
		" . tokenField() . "
	</form>
	
</div>";

?>
