<?php
  include_once '../../../inc/config.php';

  $qid = $_POST['qid'] ?? null;
  switch ($qid) {
    case 0 : fnDelete(); break;
    case 1 : fnInsert(); break;
    case 2 : fnUpdate(); break;
    case 3 : fnTable(); break;
    case 4 : fnGetById(); break;
    case 5 : fnGetByToken(); break;
    case 6 : fnCheckSlug(); break;
    default : json_encode(['success'=>false, 'message'=>'Request function invalid.']);
  }
  
  function fnInsert() {
    global $CON;
    $name_en = $_POST['name_en'];
    $name_kh = $_POST['name_kh'];
    $slug = $_POST['slug'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $token = $_POST['token'];
    // Create Business
    $SQL = "INSERT INTO `clinic` (`name_en`, `slug`, `phone`, `email`, `token`, `status`) VALUES ('$name', '$slug', '$phone', '$email', '$token', 1)";
    $RESULT = mysqli_query($CON, $SQL);
    $data = ($RESULT) ? array('status' => "success", 'message' => 'Business registered successfully!', 'token' => $token) : array('status' => 'fail', 'message' => 'Failed to register business!', 'token' => null);
    echo json_encode($data);
  }

  function fnCheckSlug() {
    global $CON;
    $slug = $_POST['slug'];
    $SQL = "SELECT * FROM `clinic` WHERE `slug` = '$slug'";
    $RESULT = mysqli_query($CON, $SQL);
    if (mysqli_num_rows($RESULT) > 0) {
        echo json_encode(array('status' => 'taken', 'message' => 'Slug already exists!'));
    } else {
        echo json_encode(array('status' => 'available', 'message' => 'Slug is available!'));
    }
  }

  function fnGetByToken() {
    global $CON;
    $token = $_POST['token'];
    $SQL = "SELECT * FROM `clinic` WHERE `token` = '$token'";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }
?>

