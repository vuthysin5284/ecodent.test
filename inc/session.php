<?php
    session_start();
	$log = $_SESSION['uid'];
    $lang = $_SESSION['lang'];
    $pgid = $_GET['pgid'];
    $USER_PERMISSION = $_SESSION['USER_PERMISSION'];
    $PERMISSION = explode(', ', $USER_PERMISSION);
    $count = 0;
    foreach($PERMISSION as $p){ if ($p == $pgid) { $count = $count + 1; } }
    if ($count == 0) { header('Location: error.php'); }
    if (!isset($log)) { header('Location: login.php'); }
    error_reporting(0);
?>