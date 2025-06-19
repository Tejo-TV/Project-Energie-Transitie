<?php
session_start();
session_unset();     // Unset all session variables
session_destroy();   // Destroy the session

// Redirect to login page or home
header("Location: ../login.php?error=uitgelogd"); // Or wherever you want
exit();
