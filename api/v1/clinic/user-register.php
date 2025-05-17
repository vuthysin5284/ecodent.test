<?php
  include_once '../../../inc/config.php';

  $qid = $_POST['qid'] ?? null;
  switch ($qid) {
    case 0 : fnDelete(); break;
    case 1 : fnInsert(); break;
    case 2 : fnUpdate(); break;
    case 3 : fnTable(); break;
    case 4 : fnGetById(); break;
    default : json_encode(['success'=>false, 'message'=>'Request function invalid.']);
  }

  function fnInsert() {
    global $CON;
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $name = $first_name .' '. $last_name;
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $token = $_POST['token'];
    $username = $email;
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    // Get business and branch id from token
    $SQL = "SELECT `id` FROM `clinic` WHERE `token` = '$token'";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    $clinic_id = $ROW['id'];
    // Create User as Owner
    $SQL = "INSERT INTO `tbl_staff` (`clinic_id`, `staff_fname`, `staff_contact`, `staff_email`, `username`, `password`, `staff_status`) VALUES ('$clinic_id', '$name', '$phone', '$email', '$username', '$password', 1)";
    $RESULT = mysqli_query($CON, $SQL);
    // Export JSON result
    $data = ($RESULT) ? array('status' => 'success', 'message' => 'Business registered successfully!') : array('status' => 'error', 'message' => 'Failed to register business!');
    echo json_encode($data);
  }
?>