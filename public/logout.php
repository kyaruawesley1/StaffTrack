<?php
session_start();

// Destroy the session
session_destroy();

// Redirect to index.html
header("Location: ../index.html");
exit;
?>
