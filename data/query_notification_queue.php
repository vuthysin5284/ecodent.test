<?php
  session_start();
  include_once ('../inc/config.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getDataRow(); }
  else if ($qid == 3) { insertData(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else if ($qid == 6) { QueueToServe(); }
  else if ($qid == 7) { fnCheckIn(); }
  else if ($qid == 8) { searchData(); }
  else { }

  function fnTable() {
    global $CON;
    $uid = $_POST['uid'];
    $LOG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $SQL = "SELECT `q`.`id`, DATE_ADD(`q`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `q`.`cust_id`, `q`.`user_id`, `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `appo_id`,  `appo_datetime`, `appo_duration`, `staff_fname`, `appo_note`, `queue_note`, `trmt_duration`, `queue_status` FROM `tbl_queue` AS `q` INNER JOIN `tbl_customer` AS `c` ON (`q`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`q`.`staff_id` = `s`.`id`) INNER JOIN `tbl_appointment` AS `a` ON (`q`.`appo_id` = `a`.`id`) INNER JOIN `tbl_treatment_duration` AS `d` ON (`q`.`queue_duration` = `d`.`id`) WHERE `queue_status` = 1 AND `cust_status` > 0";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      1 => 'cust_id',
      2 => 'cust_fname',
      3 => 'appo_datetime',
      4 => 'queue_duration',
      5 => 'staff_fname',
      6 => 'appo_note',
      7 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (cust_fname LIKE '%". $search_value ."%'";
      $SQL .= " OR `q`.`cust_id` LIKE '%".$search_value."%')";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `q`.`timestamp` DESC"; }
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
      $user_id = $ROW['user_id'];
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$user_id'"));
      $appo_timestamp = ($ROW['appo_id'] == '1') ? 'Walk In' : date('d - M - Y', strtotime($ROW['appo_datetime'])).'<br>'.date('H : i A', strtotime($ROW['appo_datetime']));
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $gender =($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $queue_timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary mb-2 showBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Serve"><span class="tf-icons bx bx-walk"></span></a>';
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning mb-2 editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger mb-2 deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      }
      
      $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      $sub_array = array(); 
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="100" width="100">'.'<h5 class="mt-2"><span class="badge bg-label-primary" style="width:100px">'.$custId.'</span></h5></center>';
      $sub_array[] = '<h4 class="body-text mb-2">'.$ROW['cust_fname'].'</h4>'.$gender.'&nbsp;&nbsp; - &nbsp;&nbsp;'.$age.' Years <br><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$ROW['cust_contact'].'<br><i class="bx bxs-map"></i> '.$ROW['cust_address'];
      $sub_array[] = '<center>'.$appo_timestamp.'</center>';
      $sub_array[] = '<center>'.$ROW['trmt_duration'].'</center>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$ROW['queue_note'].'</center>';
      $sub_array[] = '<center>'.$USER['staff_fname'].'<br>'.$queue_timestamp.'</center>';
      $sub_array[] = ($LOG['staff_position_id'] == 1 || $LOG['staff_position_id'] == 2) ? '<center>'.$btnShow.'<br>'.$btnEdit.'<br>'.$btnDelete.'</center>' : '';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
    );
    echo json_encode($output);
  }

  function getDataRow() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "SELECT `q`.*, `cust_code`, `cust_fname`, `cust_image` FROM `tbl_queue` AS `q` INNER JOIN `tbl_customer` AS `c` ON `q`.`cust_id` = `c`.`id` WHERE `q`.`id` = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnUpdate() {
    global $CON;
    $cid = $_POST['cid'];
    $cid = trim($cid, 'P-');
    $custId = (int) $cid;
    $id = $_POST['id'];
    $datetime = $_POST['datetime'];
    $duration = $_POST['duration'];
    $staffId = $_POST['staff'];
    $note = $_POST['note'];
    $SQL = "UPDATE `tbl_queue` SET `cust_id` = '$custId', `queue_duration` = '$duration', `queue_note` = '$note', `staff_id` = '$staffId' WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    $NOTID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_notification` WHERE `cust_id` = '$custId' ORDER BY `timestamp` DESC LIMIT 1"));
    $notid = $NOTID['id'];
    $NOTIFICATION = mysqli_query($CON, "UPDATE `tbl_notification` SET `notify_id` = 1, `user_id` = '$staffId' WHERE `id` = '$notid' LIMIT 1");
    echo json_encode($data);
  }
  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $NOTID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `n`.`id` FROM `tbl_notification` AS `n` INNER JOIN `tbl_queue` AS `q` ON (`n`.`cust_id` = `q`.`cust_id`) WHERE `q`.`id` = '$id' ORDER BY `n`.`timestamp` DESC LIMIT 1"));
    $notid = $NOTID['id'];
    $NOTIFICATION = mysqli_query($CON, "UPDATE `tbl_notification` SET `notify_status` = 0 WHERE `id` = '$notid' LIMIT 1");
    $SQL = "UPDATE `tbl_queue` SET `queue_status` = 0 WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function QueueToServe() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_queue` SET `queue_status` = 2 WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    if ($QUERY != TRUE) {
      $data = array('status' => 'failed');
      echo json_encode($data);
    } else {
      $QUEUE = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_queue` WHERE `id` = '$id'"));
      $queueId = $QUEUE['id'];
      $custId = $QUEUE['cust_id'];
      $staffId = $QUEUE['staff_id'];
      $userId = $QUEUE['user_id'];
      $note = $QUEUE['queue_note'];
      $queueStatus = 1;
      $TRANS = mysqli_query($CON, "INSERT INTO `tbl_serving` (`queue_id`, `cust_id`, `staff_id`, `user_id`, `serve_note`, `serve_status`) VALUES ('$queueId', '$custId', '$staffId', '$userId', '$note', '$queueStatus')");
      $NOTID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_notification` WHERE `cust_id` = '$custId' ORDER BY `timestamp` DESC LIMIT 1"));
      $notid = $NOTID['id'];
      $NOTIFICATION = mysqli_query($CON, "UPDATE `tbl_notification` SET `notify_id` = 2, `user_id` = '$staffId' WHERE `id` = '$notid' LIMIT 1");
      $data = array('status' => 'success');
      echo json_encode($data);
    }
  }
  function fnCheckIn() {
    global $CON;
    $ccode = trim($_POST['ccode'], 'CUS');
    $uid = $_POST['uid'];
    $date = date("Y-m-d");
    $start = $date.' 00:00:00';
		$end = $date.' 23:59:59';
    $CUST = mysqli_query($CON, "SELECT `id` FROM `tbl_customer` WHERE `cust_status` = 1 AND (`cust_code` LIKE '%".$ccode."%' OR `cust_fname` LIKE '%".$ccode."%') LIMIT 1");
    if (mysqli_num_rows($CUST) > 0) {
      $CUST_DATA = mysqli_fetch_assoc($CUST);
      $cid = $CUST_DATA['id'];
      $QUEUE = mysqli_query($CON, "SELECT `id` FROM `tbl_queue` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND (`cust_id` = '$cid' AND `queue_status` = 1) LIMIT 1");
      if (mysqli_num_rows($QUEUE) > 0) {
        $data = array('status' => 'inQueue');
      } else {
        $SQL = "SELECT `a`.`id` FROM `tbl_appointment` AS `a` INNER JOIN `tbl_customer` AS `c` ON (`a`.`cust_id` = `c`.`id`) WHERE (`a`.`timestamp` BETWEEN '$start' AND '$end') AND (`cust_code` LIKE '%".$ccode."%' OR `cust_fname` LIKE '%".$ccode."%') AND `appo_status` = 1";
        $QUERY = mysqli_query($CON, $SQL);
        if (mysqli_num_rows($QUERY) > 0) {
          while ($ROW = mysqli_fetch_assoc($QUERY)) {
            $id = $ROW['id'];
            $APPO = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_appointment` WHERE `id` = '$id'"));
            $appoId = $APPO['id'];
            $custId = $APPO['cust_id'];
            $staffId = $APPO['staff_id'];
            $userId = $APPO['user_id'];
            $duration = $APPO['appo_duration'];
            $note = $APPO['appo_note'];
            $queueStatus = 1;
            $UPDATE = mysqli_query($CON, "UPDATE `tbl_appointment` SET `appo_status` = 3 WHERE id = '$appoId'");
            $TRANS = mysqli_query($CON, "INSERT INTO `tbl_queue` (`appo_id`, `cust_id`, `staff_id`, `user_id`, `queue_duration`, `queue_note`, `queue_status`) VALUES ('$appoId', '$custId', '$staffId', '$userId', '$duration', '$note', '$queueStatus')");
            $NOTIFICATION = mysqli_query($CON, "INSERT INTO `tbl_notification` (`cust_id`, `notify_id`, `user_id`) VALUES ('$custId', 1, '$staffId')");
          }
          $data = array('status' => 'success');
        } else {
          $cid = $CUST_DATA['id'];
          $sid = 1;
          $appo_id = 1;
          $queueStatus = 1;
          $queue_duration = 1;
          $TRANS = mysqli_query($CON, "INSERT INTO `tbl_queue` (`appo_id`, `cust_id`, `staff_id`, `queue_duration`,`user_id`,  `queue_status`) VALUES ('$appo_id', '$cid', '$sid', '$queue_duration', '$uid', '$queueStatus')");
          $NOTIFICATION = mysqli_query($CON, "INSERT INTO `tbl_notification` (`cust_id`, `notify_id`, `user_id`) VALUES ('$cid', 1, 1)");
          $data = array('status' => 'success');
        }
      }
    }
    echo json_encode($data);
  }

  function searchData() {
    global $CON;
    $output = '';
    $str = $_POST['str'];
    $SQL = "SELECT * FROM `tbl_customer` WHERE `id` LIKE '%".$str."%' OR `cust_fname` LIKE '%".$str."%' AND `cust_status` = 1 ORDER BY `id` ASC LIMIT 10";
    $QUERY = mysqli_query($CON, $SQL);
    if(mysqli_num_rows($QUERY) > 0) {
      while ($ROW = mysqli_fetch_assoc($QUERY)) {
        $output .= '<a href="javascript:void(0);" data-id="'.$ROW['cust_fname'].'" class="list-group-item list-group-item-action data-item p-3 btnCust">';
        $output .= '<h6 class="m-0">'.$ROW['cust_fname'].'</h6>';
        $output .= '</a>';
      }
    } else {
      $output .= 'No result is matched!';
    }
    echo $output;
  }
?>