<?php
	session_start();
	unset($_SESSION['uid']);
	unset($_SESSION['system_id']); 
	unset($_SESSION['USER_PERMISSION']); 
	unset($_SESSION['user_add_perm']); 
	unset($_SESSION['user_edit_perm']); 
	unset($_SESSION['user_delete_perm']);  
	header('Location: login.php');
?>