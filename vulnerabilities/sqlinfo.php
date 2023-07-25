<?php

define( 'DVWA_WEB_PAGE_TO_ROOT', '../' );
require_once DVWA_WEB_PAGE_TO_ROOT . 'dvwa/includes/dvwaPage.inc.php';


echo"
<head>
    <link rel=\"icon\" type=\"\image/ico\" href=\"" . DVWA_WEB_PAGE_TO_ROOT . "favicon.ico\" />

    <!-- Fonts -->
	<link rel=\"preconnect\" href=\"https://fonts.googleapis.com\"> 							
    <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin>
    <link href=\"https://fonts.googleapis.com/css2?family=Asap:wght@400;500;600&display=swap\" rel=\"stylesheet\">

</head>

<li>" . dvwaExternalLinkUrlGet( 'https://en.wikipedia.org/wiki/SQL_injection' ) . "</li>
<li>" . dvwaExternalLinkUrlGet( 'https://www.netsparker.com/blog/web-security/sql-injection-cheat-sheet/' ) . "</li>
<li>" . dvwaExternalLinkUrlGet( 'https://owasp.org/www-community/attacks/SQL_Injection' ) . "</li>
<li>" . dvwaExternalLinkUrlGet( 'https://bobby-tables.com/' ) . "</li>";


?>