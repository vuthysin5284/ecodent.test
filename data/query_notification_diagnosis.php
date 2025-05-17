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
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "DELETE FROM `tbl_diagnosis` WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }
  
  function fnTable() {
    global $CON;
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT `d`.`id`, DATE_ADD(`d`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `d`.`user_id`, `d`.`cust_id`, `pres_code`, `cust_code`, `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `pres_diagnosis`, `staff_fname` 
    FROM `tbl_diagnosis` AS `d` 
    INNER JOIN `tbl_customer` AS `c` ON (`d`.`cust_id` = `c`.`id`) 
    INNER JOIN `tbl_staff` AS `s` ON (`d`.`user_id` = `s`.`id`)  "; //WHERE(`d`.`timestamp` BETWEEN '$start' AND '$end')
    $output = array();
    $columns = array(
      1 => 'cust_id',
      2 => 'cust_fname',
      3 => 'pres_diagnosis',
      4 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `cust_code` = '".$search_value."%')";
    }
    $SQL .= " GROUP BY `d`.`id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `d`.`timestamp` DESC"; }
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
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);      
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).' <br>'.date('H : i A', strtotime($ROW['timestamp']));
      
      $btnPrint = '<a href="../page/notification_prescription.php?pgid=7&cid='.$_POST['cid'].'&apid='.$_POST['apid'].'" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><span class="tf-icons bx bx-printer"></span></a>';
      // $btnShow = '<a href="../page/notification_prescription.php?cid='.$ROW['cust_id'].'&pid='.$ROW['id'].'" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Prescription"><span class="tf-icons bx bx-capsule"></span></a>';
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning editBtn mb-2"><span class="tf-icons bx bx-edit" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn mb-2"><span class="tf-icons bx bx-trash" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"></a>';
      }
      else{
        $btnDelete = "";
      }
      
      $sub_array = array(); 
      $sub_array[] = '<center>'.($i += 1).'</center>';
      $sub_array[] = '<center>'.$custId.'</center>';
      $sub_array[] =  '<center>'.$timestamp.'</center>';
      $sub_array[] = $ROW['pres_diagnosis'];
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$btnPrint.' '.$btnEdit.' '.$btnDelete.'</center>';//'.$btnShow.'<br>
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
    );
    echo json_encode($output);
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
    $SQL = "SELECT `d`.`id`, DATE_ADD(`d`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `cust_id`, `pres_code`, `pres_diagnosis`, `cust_image`, `cust_fname` FROM `tbl_diagnosis` AS `d` INNER JOIN `tbl_customer` AS `c` ON (`d`.`cust_id` = `c`.`id`) WHERE `d`.`id` = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnInsert() {
    global $CON;
    $cid = decodeId('cid', 'P-');
    $uid = $_POST['uid'];
    $diagnosis = $_POST['diagnosis'];
    $pcode = $_POST['pcode'];
    $SQL = "INSERT INTO `tbl_diagnosis` (`cust_id`, `pres_code`, `pres_diagnosis`, `user_id`) VALUES ('$cid', '$pcode', '$diagnosis', '$uid')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
  
  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $cid = $_POST['cid'];
    $cid = trim($cid, 'P-');
    $custId = (int) $cid;
    $uid = $_POST['uid'];
    $diagnosis = $_POST['diagnosis'];
    $SQL = "UPDATE `tbl_diagnosis` SET `cust_id` = '$custId', `pres_diagnosis` = '$diagnosis', `user_id` = '$uid' WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

?>