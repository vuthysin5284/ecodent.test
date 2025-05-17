<?php
  include_once ('../inc/config.php');
  include_once ("../inc/setting.php");
  
  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getStaff(); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnExist(); }
  else { }

  function fnTable() {
    $SQL = "SELECT `u`.`id`, `staff_id`, `staff_code`, `staff_image`, `staff_fname`, `staff_gender`, `staff_dob`, `staff_contact`, `staff_address`, `username` FROM `tbl_user` AS `u` INNER JOIN `tbl_staff` AS `s` ON (`u`.`staff_id` = `s`.`id`) WHERE `user_status` = 1";
    $totalQuery = mysql_query($SQL);
    $total_all_rows = mysql_num_rows($totalQuery);
    $output = array();
    $columns = array(
      1 => 'staff_id',
      2 => 'staff_fname',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`staff_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `staff_id` LIKE '%".$search_value."%')";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY  `staff_id` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysql_query($SQL);
    $count_rows = mysql_num_rows($QUERY);
    $data = array();
    $i = 0;
    while ($ROW = mysql_fetch_assoc($QUERY)) {
      $age = (date('Y') - date('Y', strtotime($ROW['staff_dob'])));
      $gender = ($ROW['staff_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $btnShow = '<a href="staff_permission.php?pgid=17&sid='.$ROW['id'].'" class="btn btn-icon btn-primary showBtn"><span class="tf-icons bx bx-cog"></span></a>';
      $StaffId = 'S-'. sprintf('%05d', $ROW['staff_id']);
      $folder = ($ROW['staff_image'] == '0') ? '' : $StaffId.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $sub_array = array();
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['staff_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="100" width="100">'.'<h5 class="mt-2"><span class="badge bg-label-primary" style="width:100px">'.$StaffId.'</span></h5></center>';
      $sub_array[] = '<h4 class="body-text mb-2">'.$ROW['staff_fname'].'</h4>'.$gender.'&nbsp;&nbsp; - &nbsp;&nbsp;'.$age.' Years <br><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$ROW['staff_contact'].'<br><i class="bx bxs-map"></i> '.$ROW['staff_address'];
      $sub_array[] = '<center>'.$ROW['username'].'</center>';
      $sub_array[] = '<center>********</center>';
      $sub_array[] = '<center>'.$btnShow.'<center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered'=> $total_all_rows,
      'data'=> $data,
    );
    echo  json_encode($output);
  }

  function getStaff() {
    $id = $_POST['name'];
    $SQL = "SELECT `s`.`id`, `staff_fname`, `staff_code`, `staff_image`, `default_permission` FROM `tbl_staff` AS `s` INNER JOIN `tbl_staff_position` AS `p` ON (`s`.`staff_position_id` = `p`.`id`) WHERE `s`.`id` = '$id' LIMIT 1";
    $QUERY = mysql_query($SQL);
    $ROW = mysql_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnInsert() {
    $staffId = decodeId('sid', 'S-');
    $name = $_POST['nickname'];
    $username = $_POST['username'];
    $permission = $_POST['permission'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $userStatus = 1;
    $sql = "INSERT INTO `tbl_user` (`staff_id`, `nickname`, `username`, `password`, `user_permission`, `user_status`) VALUES ('$staffId', '$name', '$username', '$password', '$permission', '$userStatus')";
    $query = mysql_query($sql); 
    $last_id = mysql_insert_id($con);
    $data = ($query == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }
  
  function fnExist() {
    $str = $_POST['str'];
    $SQL = "SELECT `username` FROM `tbl_user`";
    $QUERY = mysql_query($SQL);
    $data = array('status' => 'False');
    while ($ROW = mysql_fetch_assoc($QUERY)){
      if ($ROW['username'] == $str) {
        $data = array('status' => 'True');
      }
    }
    echo json_encode($data);
  }
?>