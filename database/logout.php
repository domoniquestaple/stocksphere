<?php
	session_start();

	// Remove all session variables
	session_unset();

	// Destroy
	session_destroy();

	header('location: ../login.php');
?>