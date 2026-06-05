<?php
session_start();

// Presentation Layer
// This page logs out the customer manager
// by removing the manager session.

unset($_SESSION["IsCustomerManager"]);

// After logout, return to the home page.
header("Location: index.php");
exit;
?>