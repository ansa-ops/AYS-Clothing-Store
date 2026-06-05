<?php
session_start();

// Presentation Layer
// This page logs the customer out of the system
// by destroying all session data.

// Remove all session information.
session_destroy();

// Return customer to the login page.
header("Location: login.php");
exit;
?>