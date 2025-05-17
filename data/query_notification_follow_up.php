<?php
  session_start();
  include_once ('../inc/config.php');

  $qid = $_POST['qid'];
  if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { confirmAppointment(); }
  else { }

  function fnTable() {
    global $CON;
    $uid = $_POST['uid'];
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $LOG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $SQL = "SELECT `a`.`id`, DATE_ADD(`a`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `a`.`staff_id`, `a`.`user_id`, `cust_id`, `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `appo_datetime`, `appo_duration`, `staff_fname`, `appo_note`, `trmt_duration` FROM `tbl_appointment` AS `a` INNER JOIN `tbl_customer` AS `c` ON (`a`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`a`.`staff_id` = `s`.`id`) INNER JOIN `tbl_treatment_duration` AS `td` ON (`a`.`appo_duration` = `td`.`id`) WHERE (`a`.`appo_datetime` BETWEEN '$start' AND '$end') AND `appo_status` = 2";
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
      if ($LOG['staff_position_id'] == 1 || $uid == $ROW['staff_id']) {
        $user_id = $ROW['user_id'];
        $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$user_id'"));
        $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
        $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);      
        $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
        $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
        $datetime = date('d - M - Y', strtotime($ROW['appo_datetime'])).'<br>'.date('h : i A', strtotime($ROW['appo_datetime']));
        $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('h : i A', strtotime($ROW['timestamp']));
        $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary showBtn mb-2" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Confirm"><span class="tf-icons bx bx-check"></span></a>';
        // edit
        if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
          $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning editBtn mb-2" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></span></a>';
        }
        else{
          $btnEdit = "";
        }
        
        // delete
        if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
          $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn mb-2" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
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
        $sub_array[] = '<center>'.$USER['staff_fname'].'<br>'.$timestamp.'</center>';
        $sub_array[] = '<center>'.$btnShow.'<br>'.$btnEdit.'<br>'.$btnDelete.'</center>';
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

  function confirmAppointment() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_appointment` SET `appo_status` = 1 WHERE `id` = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    if ($QUERY != TRUE) {
      $data = array('status' => 'failed');
    } else {
      $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_appointment` WHERE `id` = '$id'"));
      if ($ROW['appo_repeat'] == 1) {
        $cid = $ROW['cust_id'];
        $sid = $ROW['staff_id'];
        $uid = $ROW['user_id'];
        $duration = $ROW['appo_duration'];
        $note = $ROW['appo_note'];
        $status = 2;
        $datetime = date("Y-m-d", strtotime($ROW['appo_datetime'].' +1 month')).' 09:00:00';
        mysqli_query($CON, "INSERT INTO `tbl_appointment` (`cust_id`, `appo_datetime`, `appo_duration`, `appo_note`, `appo_status`, `staff_id`, `user_id`) VALUES ('$cid', '$datetime', '$duration', '$note', '$status', '$sid', '$uid')");
      }
      $data = array('status' => 'success');
    }
    echo json_encode($data);;
  }

?>