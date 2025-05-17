<?php
  session_start();
  include_once ('../inc/config.php');
  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $next_appo = $_POST['date'].' 09:00:00';
    $note = $_POST['note'];
    $SERVE = mysqli_fetch_assoc(mysqli_query($CON, "SELECT DATE_ADD(`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `cust_id`, `staff_id`, `user_id` FROM `tbl_serving` WHERE `id` = '$id'"));
    $last_appo = $SERVE['timestamp'];
    $cid = $SERVE['cust_id'];
    $sid = $SERVE['staff_id'];
    $uid = $SERVE['user_id'];
    $SQL = "INSERT INTO `tbl_followup` (`cust_id`, `last_appointment`, `staff_id`, `fup_note`, `next_appointment`, `user_id`, `fup_status`) VALUES ('$cid', '$last_appo', '$sid', '$note', '$next_appo', '$uid', 1)";
    $QUERY = mysqli_query($CON, $SQL);
    if ($QUERY == TRUE) {
      $UPDATE_SERVING = mysqli_query($CON, "UPDATE `tbl_serving` SET `serve_status` = 2 WHERE `id` = '$id'");
      $NOTID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_notification` WHERE `cust_id` = '$cid' ORDER BY `timestamp` DESC LIMIT 1"));
      $notid = $NOTID['id'];
      $NOTIFICATION = mysqli_query($CON, "UPDATE `tbl_notification` SET `notify_status` = 0 WHERE `id` = '$notid' LIMIT 1");
      $data = array('status' => 'success');
    } else { $data = array('status' => 'failed'); }
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT `sv`.`id`, DATE_ADD(`sv`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `sv`.`cust_id`, `cust_code`, `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `queue_id`,  `queue_duration`, `staff_fname`, `serve_note`, `trmt_duration`, `serve_status` FROM `tbl_serving` AS `sv` INNER JOIN `tbl_customer` AS `c` ON (`sv`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`sv`.`staff_id` = `s`.`id`) INNER JOIN `tbl_queue` AS `q` ON (`sv`.`queue_id` = `q`.`id`) INNER JOIN `tbl_treatment_duration` AS `d` ON (`q`.`queue_duration` = `d`.`id`) WHERE (`sv`.`timestamp` BETWEEN '$start' AND '$end') AND `serve_status` = 1";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      1 => 'cust_id',
      2 => 'cust_fname',
      3 => 'timestamp',
      4 => 'queue_duration',
      5 => 'staff_fname',
      6 => 'serve_note',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (cust_fname LIKE '%". $search_value ."%'";
      $SQL .= " OR `sv`.`cust_id` LIKE '%".$search_value."%')";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `timestamp` DESC"; }
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
      if ($ROW['cust_gender'] == 0) $gender = '<i class="bx bx-female-sign"></i> Female';
      else $gender = '<i class="bx bx-male-sign"></i> Male';
      $serve_timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $btnShow = '<a href="patient_treatment_plan.php?pgid=7&cid='.$ROW['cust_code'].'" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><span class="tf-icons bx bx-show"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger deleteBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Check Out"><span class="tf-icons bx bx-log-out"></span></a>';
      $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      $sub_array = array(); 
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="100" width="100">'.'<h5 class="mt-2"><span class="badge bg-label-primary" style="width:100px">'.$custId.'</span></h5></center>';
      $sub_array[] = '<h4 class="body-text mb-2">'.$ROW['cust_fname'].'</h4>'.$gender.'&nbsp;&nbsp; - &nbsp;&nbsp;'.$age.' Years <br><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$ROW['cust_contact'].'<br><i class="bx bxs-map"></i> '.$ROW['cust_address'];
      $sub_array[] = '<center>'.$serve_timestamp.'</center>';
      $sub_array[] = '<center>'.$ROW['trmt_duration'].'</center>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$ROW['serve_note'].'</center>';
      $sub_array[] = '<center>'.$btnShow.'<br>'.$btnDelete.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw'=> intval($_POST['draw']),
      'recordsTotal' =>$count_rows ,
      'recordsFiltered'=> $total_all_rows,
      'data'=>$data,
    );
    echo json_encode($output);
  }
?>