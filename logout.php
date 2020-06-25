<?php
	session_start();
	session_destroy();
	header("Location: /DBFinalProject/index.php");
	die();
?>