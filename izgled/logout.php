<?php
session_start();
session_unset();
session_destroy();
header("Location: Sajt.php"); // Promenjeno preusmeravanje na Sajt.php
exit;
?>