<?php
  include_once ('../inc/config.php');
  include_once ('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 1) { fnChangePwd(); }
  else if ($qid == 2) { getJSRow('tbl_staff'); }
  else { }

  function fnChangePwd() {
    global $CON;
    $uid = $_POST['uid'];
    $username = $_POST['username'];
    $oldpwd = $_POST['oldpwd'];
    $newpwd = $_POST['newpwd'];
    $confirmpwd = $_POST['confirmpwd'];
    $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `password` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    if (password_verify($oldpwd, $USER['password']) == 1) {
      if ($newpwd == $confirmpwd) {
        $password = password_hash($newpwd, PASSWORD_DEFAULT);
        $SQL = "UPDATE `tbl_staff` SET `username` = '$username', `password` = '$password' WHERE `id` = '$uid'";
        $QUERY = mysqli_query($CON, $SQL);
        $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
      } else { $data = array('status' => 'imcPwd'); }
    } else { $data = array('status' => 'incPwd'); }
    echo json_encode($data);
  }
?>