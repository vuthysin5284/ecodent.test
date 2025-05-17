<?php
	$CON = new mysqli('localhost', 'root', 'root', 'khemalin_dental_clinic');
	if ($CON -> connect_errno) {
		echo "Failed to connect to MySQL: " . $CON -> connect_error;
		exit();
	}
	$CON -> set_charset("utf8");
	error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED);
	date_default_timezone_set('Asia/Phnom_Penh');
	 
	// $file_path = '/home/khemalin/dental.khemalink.com/images/'; 
	$file_path = '../images/'; 
?>
