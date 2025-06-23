<?php
// Initialize the session
session_start();

// Unset all of the session variables
$_SESSION = array();

// Destroy the session.
session_destroy();

// Redirect to login page
// logout.php is in 'Login System/', and login_page.php is also in 'Login System/'.
// So, the direct file name is correct for the redirect.
header("location: login_page.php");
exit;
?>