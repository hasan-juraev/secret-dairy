<?php
		require_once "config.php";	
		unset($_SESSION['access_token']);
		$gClient->revokeToken($_SESSION['access_token']);
		session_destroy();
		header('Location: signup.php');
		exit();

		echo "hahah";
	

	
?>