<?php
  include_once ("../inc/config.php");
	include_once ("../securimage/securimage.php");
  
  $qid = $_POST['qid'];
  if ($qid == 1) { fnLogin(); }
  else if ($qid == 2) { fnBackup(); }
  else {}

  function fnBackup() {
    global $CON;
    $SQL = "SELECT `staff_position_id` FROM `tbl_user_log` AS `u` INNER JOIN `tbl_staff` AS `s` ON (`u`.`user_id` = `s`.`id`) WHERE `staff_position_id` = 1 ORDER BY `u`.`timestamp` DESC LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    if ($ROW['staff_position_id'] == 1) {
      include_once("../inc/backUpDB.php");
    }
  }

  function fnLogin() {
    global $CON;
    $securimage = new Securimage();
    $username = $_POST['username'];
    $password = $_POST['password'];
    $captcha = $_POST['ccode'];
    if (!preg_match('/[\'^L$%&*()}{@#~?><>,|=_+¬-]/', $_POST["username"])){
      $username = htmlspecialchars($_POST["username"], ENT_QUOTES);
    }
    // if ($securimage -> check($captcha) == true) {
      $SQL = "SELECT * FROM `tbl_staff` WHERE `username` = '$username' AND `staff_status` = 1 LIMIT 1";
      $QUERY = mysqli_query($CON, $SQL);
      if (mysqli_num_rows($QUERY) > 0) {
        $ROW = mysqli_fetch_assoc($QUERY);
        if (password_verify($password, $ROW['password']) == 1) {
          $id = $ROW['id'];
          $lang = $ROW['user_lang'];
          $SQLs = "INSERT INTO `tbl_user_log` (`user_id`, `log_status`, `lang`) VALUES ('$id', 1, 1)";
          $QUERYs = mysqli_query($CON, $SQLs);
		      $_SESSION['USER_PERMISSION'] = $ROW['user_permission'];
		      $_SESSION['user_add_perm'] = $ROW['user_add_perm'];
		      $_SESSION['user_edit_perm'] = $ROW['user_edit_perm'];
		      $_SESSION['user_delete_perm'] = $ROW['user_delete_perm'];
          $_SESSION['uid'] = $id;
          $_SESSION['business_id'] = $ROW['clinic_id'];
          $_SESSION['khema_code'] = $ROW['khema_code'];
          $_SESSION['lang'] = $lang;
          $data = array('status' => 'true');
        }
      } else { $data = array('status' => 'error_log'); }
    // } else { $data = array('status' => 'error_captchar'); }
    echo json_encode($data);
  }
?>