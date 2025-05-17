<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];

  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getCust(); }
  else if ($qid == 3) { getDataRow(); }
  else if ($qid == 4) { fnInsert(); }
  else if ($qid == 5) { fnUpdate(); }
  else if ($qid == 6) { AppointmentToServe(); }
  else if ($qid == 7) { fnTest(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_appointment` SET `appo_status` = 0 WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }
  
  function fnTable() {
    global $CON;
    $uid = $_POST['uid'];
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $LOG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $SQL = "SELECT `a`.`id`, DATE_ADD(`a`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `a`.`staff_id`, `a`.`user_id`, `cust_id`, `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `appo_datetime`, `appo_duration`, `staff_fname`, `appo_note`, `trmt_duration` FROM `tbl_appointment` AS `a` INNER JOIN `tbl_customer` AS `c` ON (`a`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`a`.`staff_id` = `s`.`id`) INNER JOIN `tbl_treatment_duration` AS `td` ON (`a`.`appo_duration` = `td`.`id`) WHERE (`a`.`appo_datetime` BETWEEN '$start' AND '$end') AND `appo_status` = 1";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      1 => 'cust_id',
      2 => 'cust_fname',
      3 => 'appo_datetime',
      4 => 'appo_duration',
      5 => 'staff_fname',
      6 => 'appo_note',
      7 => 'timestamp',
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
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      if ($LOG['staff_position_id'] == 1 || $LOG['staff_position_id'] == 2 || $uid == $ROW['staff_id']) {
        $user_id = $ROW['user_id'];
        $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$user_id'"));
        $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
        $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);      
        $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
        $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
        $datetime = date('d - M - Y', strtotime($ROW['appo_datetime'])).' <br>'.date('h : i A', strtotime($ROW['appo_datetime']));
        $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).' <br>'.date('h : i A', strtotime($ROW['timestamp']));
        $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Queue"><span class="tf-icons bx bx-walk"></span></a>';
        // edit
        if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
          $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning editBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></span></a>';
        }
        else{
          $btnEdit = "";
        }
        
        // delete
        if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
          $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
        }
        else{
          $btnDelete = "";
        }
        $sub_array = array(); 
        $sub_array[] = '<center>'.$i = ($i + 1).'</center>';
        $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image']. '.jpg" alt="user-avatar" class="d-block rounded mt-2" height="100" width="100">'.'<h5 class="mt-2"><span class="badge bg-label-primary" style="width:100px">'.$custId.'</span></h5></center>';
        $sub_array[] = '<h4 class="body-text mb-2">'.$ROW['cust_fname'].'</h4>'.$gender.'&nbsp;&nbsp; - &nbsp;&nbsp;'.$age.' Years <br><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$ROW['cust_contact'].'<br><i class="bx bxs-map"></i> '.$ROW['cust_address'];
        $sub_array[] = '<center>'.$datetime.'</center>';
        $sub_array[] = '<center>'.$ROW['trmt_duration'].'</center>';
        $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
        $sub_array[] = '<center>'.$ROW['appo_note'].'</center>';
        $sub_array[] = '<center>'.$USER['staff_fname'].' <br>'.$timestamp.'</center>';
        $sub_array[] = ($LOG['staff_position_id'] == 1 || $LOG['staff_position_id'] == 2 ) ? '<center>'.$btnShow.'<br>'.$btnEdit.'<br>'.$btnDelete.'</center>' : '';
        $data[] = $sub_array;
      }
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
    );
    echo  json_encode($output);
  }

  function getCust() {
    global $CON;
    $id = $_POST['name'];
    $SQL = "SELECT `id`, `cust_code`, `cust_image` FROM `tbl_customer` WHERE `id` = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function getDataRow() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "SELECT `a`.`id`, DATE_ADD(`a`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `cust_id`, `cust_image`, `cust_code`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `appo_datetime`, `appo_duration`, `a`.`staff_id`, `appo_note`, `staff_fname` FROM `tbl_appointment` AS `a` INNER JOIN `tbl_customer` AS `c` ON (`a`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`a`.`staff_id` = `s`.`id`) WHERE `a`.`id` = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnInsert() {
    global $CON;
    $cid = decodeId('cid', 'P-');
    $sid = $_POST['sid'];
    $uid = $_POST['uid'];
    $datetime = $_POST['datetime'];
    $duration = $_POST['duration'];
    $note = $_POST['note'];
    $uid = $_POST['uid'];
    $status = 1;
    $SQL = "INSERT INTO `tbl_appointment` (`cust_id`, `appo_datetime`, `appo_duration`, `appo_note`, `appo_status`, `staff_id`, `user_id`) VALUES ('$cid', '$datetime', '$duration', '$note', '$status', '$sid', '$uid')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
  
  function fnUpdate() {
    global $CON;
    $cid = $_POST['cid'];
    $cid = trim($cid, 'P-');
    $custId = (int) $cid;
    $sid = $_POST['sid'];
    $uid = $_POST['uid'];
    $id = $_POST['id'];
    $datetime = $_POST['datetime'];
    $duration = $_POST['duration'];
    $note = $_POST['note'];
    $SQL = "UPDATE `tbl_appointment` SET `cust_id` = '$custId', `appo_datetime` = '$datetime', `appo_duration` = '$duration', `appo_note` = '$note', `staff_id` = '$sid', `user_id` = '$uid' WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function AppointmentToServe() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_appointment` SET `appo_status` = 3 WHERE `id` = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    if ($QUERY != TRUE) {
      $data = array('status' => 'failed');
    } else {
      $APPO = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_appointment` WHERE `id` = '$id'"));
      $appoId = $APPO['id'];
      $custId = $APPO['cust_id'];
      $staffId = $APPO['staff_id'];
      $userId = $APPO['user_id'];
      $duration = $APPO['appo_duration'];
      $note = $APPO['appo_note'];
      $queueStatus = 1;
      $TRANS = mysqli_query($CON, "INSERT INTO `tbl_queue` (`appo_id`, `cust_id`, `staff_id`, `user_id`, `queue_duration`, `queue_note`, `queue_status`) VALUES ('$appoId', '$custId', '$staffId', '$userId', '$duration', '$note', '$queueStatus')");
      $NOTIFICATION = mysqli_query($CON, "INSERT INTO `tbl_notification` (`cust_id`, `notify_id`, `user_id`) VALUES ('$custId', 1, '$staffId')");
      $data = array('status' => 'success');
    }
    echo json_encode($data);
  }

?>