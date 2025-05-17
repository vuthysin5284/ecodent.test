<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  
  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow ('tbl_appointment'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_appointment` SET `appo_status` = 0 WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == true) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }
  function fnTable() {
    global $CON;
    $cid = $_POST['cid'];
    $uid = $_POST['uid'];
    $SQL = "SELECT `a`.`id`, `cust_id`, `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `appo_datetime`, `trmt_duration`, `staff_fname`, `appo_note`, `a`.`user_id` FROM `tbl_appointment` AS `a` INNER JOIN `tbl_customer` AS `c` ON (`a`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`a`.`staff_id` = `s`.`id`) INNER JOIN `tbl_treatment_duration` AS `d` ON (`a`.`appo_duration` = `d`.`id`) WHERE (`appo_status` BETWEEN 1 AND 2) AND `cust_code`='$cid' ";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      1 => 'appo_datetime',
      2 => 'appo_duration',
      3 => 'staff_fname',
      4 => 'appo_note',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `cust_id` LIKE '%".$search_value."%')";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `appo_datetime` ASC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    }
    $query = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($query);
    $data = array();
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($query)) {
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $appo_date = date('d - M - Y', strtotime($ROW['appo_datetime']));
      $appo_time = date('H : i A', strtotime($ROW['appo_datetime']));
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      }
      $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary showBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Next Schedule"><span class="tf-icons bx bx-show"></span></a>';
      
      $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);
      $sub_array = array(); 
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center>'.$appo_date.'<br>'.$appo_time.'</center>';
      $sub_array[] = '<center>'.$ROW['trmt_duration'].'</center>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$ROW['appo_note'].'</center>';
      $UID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `user_id` = '$uid'"));
      if ($UID['staff_position_id'] == 1 || $uid == $ROW['user_id']) { 
        $sub_array[] = '<center>'.$btnShow.' '.$btnEdit.' '.$btnDelete.'</center>'; 
      } else { $sub_array[] = '<center></center>'; }
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
    );
    echo  json_encode($output);
  }

  function fnInsert() {
    global $CON;
    $cid = $_POST['cid'];
    $sid = $_POST['sid'];
    $uid = $_POST['uid'];
    $note = $_POST['note'];
    $repeat = $_POST['repeat'];
    $datetime = $_POST['datetime'];
    $duration = $_POST['duration'];
    $status = 2;
    $CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_customer` WHERE `cust_code` = '$cid' LIMIT 1"));
    $id = $CUST['id'];
    $SQL = "INSERT INTO `tbl_appointment` (`cust_id`, `appo_datetime`, `appo_duration`, `appo_note`, `appo_repeat`, `appo_status`, `staff_id`, `user_id`) VALUES ('$id', '$datetime', '$duration', '$note', '$repeat', '$status', '$sid', '$uid')";
    $query = mysqli_query($CON, $SQL);
    $data = ($query == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
  
  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $sid = $_POST['sid'];
    $note = $_POST['note'];
    $repeat = $_POST['repeat'];
    $datetime = $_POST['datetime'];
    $duration = $_POST['duration'];
    $status = 2;
    $SQL = "UPDATE `tbl_appointment` SET `appo_datetime` = '$datetime', `appo_duration` = '$duration', `appo_note` = '$note', `staff_id` = '$sid', `appo_repeat` = '$repeat', `appo_status` = '$status' WHERE id = '$id'";
    $query = mysqli_query($CON, $SQL);
    $data = ($query == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
?>