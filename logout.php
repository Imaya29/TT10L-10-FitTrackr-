<?php
// Initialize the session
session_start();
 
// Unset all of the session variables
$_SESSION = array();
 
// Destroy the session
session_destroy();
 
// Redirect to login page after logout
header("location: /TT10L-10-FitTrackr-/login/login.html");
exit;
?>
