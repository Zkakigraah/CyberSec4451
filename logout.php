<?php
// 1. Find the active session
session_start();

// 2. Erase all session variables
$_SESSION = array();

// 3. Destroy the session entirely
session_destroy();

// 4. Redirect back to the login page
header("Location: login.php");
exit;
?>
