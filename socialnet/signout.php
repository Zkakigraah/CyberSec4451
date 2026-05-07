<?php
session_start();
$_SESSION = array(); // Clear session variables
session_destroy();   // Destroy the session completely
header("Location: signin.php"); // Redirect to login
exit;
?>
