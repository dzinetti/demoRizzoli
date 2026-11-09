<?php
require_once __DIR__."/../config/session.php";
session_destroy();
header("Location: ../../app_html/login.html");
exit;
?>
