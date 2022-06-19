<?php
	session_start();
	require_once "Composer/vendor/autoload.php";
	$gClient = new Google_Client();
	$gClient->setClientId("1075997450871-igb9pvuodnf81ljqnhpncie35l4p9j2m.apps.googleusercontent.com");
	$gClient->setClientSecret("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");
	$gClient->setApplicationName("Google Login");
	$gClient->setRedirectUri("https://hasan-juraev25.stackstaging.com/secretdairy/g-callback.php");
	$gClient->addScope("https://www.googleapis.com/auth/plus.login https://www.googleapis.com/auth/userinfo.email");
?>
