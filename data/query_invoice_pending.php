<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];

  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_invoice_patient` SET `inv_status` = 0 WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }
  
  function fnTable() {
    global $CON;
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
    $dates = explode('-', $date);
    $start = str_replace('/', '-', $dates[0]).' 00:00:00';
    $end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT `ip`.`id`, DATE_ADD(`ip`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `ip`.`user_id`, `inv_title`, `inv_code`, `cust_id`, `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `staff_fname` FROM `tbl_invoice_patient` AS `ip` INNER JOIN `tbl_customer` AS `c` ON (`ip`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`ip`.`staff_id` = `s`.`id`) WHERE (`ip`.`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` = 2";
    $QUERY1 = mysqli_query($CON, $SQL);   
    $total_all_rows = mysqli_num_rows($QUERY1);
    $output = array();
    $columns = array(
      1 => 'cust_id',
      2 => 'cust_fname',
      3 => 'inv_title',
      4 => 'staff_id',
      5 => 'inv_status',
      6 => 'timestamp',
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
    } else { $SQL .= " ORDER BY `ip`.`timestamp` DESC"; }
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
      $uid = $ROW['user_id'];
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $btnShow = '<a href="invoice_payment.php?pgid='.$_POST['pgid'].'&icode='.$ROW['inv_code'].'" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Payment"><span class="tf-icons bx bx-receipt"></span></a>';
      // $btnPrint = '<a href="print_invoice_draft.php?pgid='.$_POST['pgid'].'&icode='.$ROW['inv_code'].'" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><span class="tf-icons bx bx-printer"></span></a>';
      $btnPrint = '<a href="print_invoice_patient.php?pgid='.$_POST['pgid'].'&icode='.$ROW['inv_code'].'&pmid=0" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><span class="tf-icons bx bx-printer"></span></a>';
       
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      }
      
      $status = '<span class="badge bg-warning p-2">PENDING</span>';
      $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);      
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      $sub_array = array(); 
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image']. '.jpg" alt="user-avatar" class="d-block rounded mt-2" height="100" width="100">'.'<h5 class="mt-2"><span class="badge bg-label-primary" style="width:100px">'.$custId.'</span></h5></center>';
      $sub_array[] = '<h4 class="body-text mb-2">'.$ROW['cust_fname'].'</h4>'.$gender.'&nbsp;&nbsp; - &nbsp;&nbsp;'.$age.' Years <br><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$ROW['cust_contact'].'<br><i class="bx bxs-map"></i> '.$ROW['cust_address'];
      $sub_array[] = '<center>'.$ROW['inv_title'].'</center>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] = '<center>'.$USER['staff_fname'].'<br>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$btnPrint.' '.' '.$btnShow.' '.$btnDelete.'</center>';
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
?>