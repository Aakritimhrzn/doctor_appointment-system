<?php
require_once '../config/db.php';

// Destroy all sessions
session_destroy();

// Redirect to admin login page
header("Location: login.php");
exit();
?>