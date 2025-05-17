<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];

  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnTablePaymentList(); }
  else if ($qid == 3) { receivePayment(); }
  else if ($qid == 4) { fnTableReceivedList(); }
  else if ($qid == 5) { fnTableReceivedItemList(); }
  else if ($qid == 6) { getDataReport(); }
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
    $menthod = $_POST['menthod'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT 
      SUM(distinct inv_grandtotal) AS `total`, 
      SUM(distinct inv_remain) AS `remain`, 
      SUM(distinct inv_grandtotal)-SUM(distinct inv_remain) AS `paid`,  
      `i`.`id`, 
	    pm.payment_method, 
      DATE_ADD(`i`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, 
      `inv_code`, `cust_id`, `cust_fname`, `cust_image`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `staff_fname`, `inv_title`, `inv_status` 
    FROM `tbl_invoice_patient` AS `i` 
    INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) 
    INNER JOIN `tbl_staff` AS `s` ON (`i`.`staff_id` = `s`.`id`) 
    INNER JOIN tbl_invoice_payment ip ON ip.inv_id = i.id  
    INNER JOIN tbl_payment_method pm ON pm.id = ip.paym_id
    WHERE (`i`.`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` > 0
    AND ('ALL'= '$menthod' or (case when ip.paym_id=1 then 'CASH' else 'BANK' end) = '$menthod' )";
    $output = array();
    $columns = array(
      0 => 'inv_title',
      2 => 'cust_fname',
      3 => 'total',
      4 => 'remain',
      5 => 'inv_status',
      6 => 'staff_fname',
      7 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `i`.`id` LIKE '%".$search_value."%')";
    }
    if ($_POST['sid'] != '') {
      $sid = $_POST['sid'];
      $SQL .= " AND `i`.`staff_id` = '$sid'";
    }
    if ($_POST['zid'] != '') {
      $zid = $_POST['zid'];
      $SQL .= " AND `inv_status` = '$zid'";
    }
    if ($_POST['pyid'] != '') {
      $pyid = $_POST['pyid'];
      $SQL .= " AND ip.paym_id = '$pyid'";
    }

    $SQL .= " GROUP BY `i`.`id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `i`.`timestamp` DESC"; }
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
      $cid = $ROW['cust_id'];
      $total = ($ROW['total'] == '') ? 0 : $ROW['total'];
      $remain = ($ROW['remain'] == '') ? 0 : $ROW['remain'];
      $paid = ($ROW['paid'] == '') ? 0 : $ROW['paid'];
      $grandTotal = $grandTotal + $total;
      $remainTotal = $remainTotal + $remain;
      $paidTotal = $paidTotal + $paid;
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'  '.date('H : i A', strtotime($ROW['timestamp']));
      $custId = 'P-'. sprintf('%05d', $cid);      
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      if ($ROW['inv_status'] == 2) {
        $status = '<span class="badge bg-warning p-2">PATIAL</span>';
      } else if ($ROW['inv_status'] == 3) {
        $status = '<span class="badge bg-success p-2">PAID</span>';
      } else {
        $status = '<span class="badge bg-secondary p-2">QUOTE</span>';
      }
      $sub_array = array(); 
      $sub_array[] = '<center><a href="../page/invoice_payment.php?pgid=22&icode='.$ROW["inv_code"].'">'.$ROW['inv_title'].'</a></center>';
      // $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image']. '.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
      $sub_array[] = $ROW['cust_fname'];
      // $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($total,2).'</span></div>';
      // $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($remain,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($paid,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>'.$ROW['payment_method'].'</span></div>';
      // $sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] =  $ROW['staff_fname'];
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
      // 'grandTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($grandTotal,2).'</span></div>',
      // 'remainTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($remainTotal,2).'</span></div>',
      'paidTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($paidTotal,2).'</span></div>',
    );
    echo json_encode($output);
  }
  // 
  function fnTableReceivedList() {
    global $CON;
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
    $dates = explode('-', $date);
    $start = str_replace('/', '-', $dates[0]).' 00:00:00';
    $end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT *  FROM `tbl_receipt_payment_h` WHERE ( `created_date` BETWEEN '$start' AND '$end') AND  `status` = 1";  //( `created_date` BETWEEN '$start' AND '$end') AND 
    $QUERY1 = mysqli_query($CON, $SQL);   
    $total_all_rows = mysqli_num_rows($QUERY1);
    $output = array();
    $columns = array(
      1 => 'entry_date',
      2 => 'post_date',
      3 => 'created_by',
      4 => 'total_amount',
      5 => 'count_invoice',
      6 => 'remark',
      7 => 'id'
    );
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `id` DESC"; }
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
      $uid = $ROW['created_by']; 
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1")); 
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])); 
      $sub_array = array(); 
      $sub_array[] = '<center>'.$ROW['id'].'<center>'; 
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center>'.$ROW['entry_date'].'</center>';
      $sub_array[] = '<center>'.$ROW['post_date'].'</center>'; 
      $sub_array[] = '<center>'.$USER['staff_fname'].'</center>';
      $sub_array[] = '<center>$ '.$ROW['total_amount'].'</center>'; // amount
      $sub_array[] = '<center>'.$ROW['count_invoice'].'</center>';//   
      $sub_array[] =  $ROW['remark'] ;// Method 
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
  // 
  function fnTablePaymentList() {
    global $CON;
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
    $dates = explode('-', $date);
    $start = str_replace('/', '-', $dates[0]).' 00:00:00';
    $end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT `ipt`.`id`, DATE_ADD(`ip`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `ip`.`user_id`, `ip`.`staff_id`, `inv_title`, 
      `inv_code`, `cust_id`, `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `staff_fname`,
      `ipt`.`payment_amount`,`ipt`.`paym_id`,`ipt`.`code_number` 
      FROM `tbl_invoice_patient` AS `ip` 
      INNER JOIN `tbl_invoice_payment` AS `ipt` ON (`ipt`.`inv_id` = `ip`.`id`) 
      INNER JOIN `tbl_customer` AS `c` ON (`ip`.`cust_id` = `c`.`id`) 
      INNER JOIN `tbl_staff` AS `s` ON (`ip`.`staff_id` = `s`.`id`) 
      WHERE `ipt`.is_received=0";//(`ip`.`timestamp` BETWEEN '$start' AND '$end') AND 
    $QUERY1 = mysqli_query($CON, $SQL);   
    $total_all_rows = mysqli_num_rows($QUERY1);
    $output = array();
    $columns = array(
      1 => 'cust_id',
      2 => 'cust_fname',
      3 => 'inv_title',
      4 => 'staff_id',
      5 => 'inv_status',
      6 => 'timestamp' 
    );
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `ip`.`id` DESC"; }
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
      $paym_id = $ROW['paym_id'];
      $uid = $ROW['user_id'];
      $MD = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `payment_method` FROM `tbl_payment_method` WHERE `id` = '$paym_id' "));
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp']));
      $btnShow = '<a href="invoice_payment.php?pgid=7&icode='.$ROW['inv_code'].'" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="View Details"><span class="tf-icons bx bx-receipt"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Cancel Receive Payment"><span class="tf-icons bx bx-trash"></a>';
      $status = '<span class="badge bg-success p-2">PAID</span>';
      $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);      
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      $sub_array = array(); 
      $sub_array[] = '<center><input class="form-check-input checkitem" type="checkbox" name="paid_id[]" value="'.$ROW['id'].'"/><center>'; 
      $sub_array[] = '<center>'.$ROW['inv_title'].'</center>';
      $sub_array[] = $ROW['cust_fname'];
      $sub_array[] = '<center>'.$USER['staff_fname'].'</center>';
      $sub_array[] = '<center>$ '.$ROW['payment_amount'].'</center>'; // amount
      $sub_array[] = '<center>'.$MD['payment_method'].'</center>';// Method
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';//  
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$ROW['code_number'].'</center>';
      // $sub_array[] = '<center>'.$btnShow.' '.$btnDelete.'</center>';// 
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
  // 
  function fnTableReceivedItemList() {
    global $CON;
    $head_id = $_POST['head_id'];  
    $SQL = "SELECT rpi.id,rpi.inv_code, rpi.inv_date, rpi.amount, s.staff_fname, c.cust_fname, pm.payment_method,rpi.created_inv_by,rpi.paid_code
      FROM `tbl_receipt_payment_item` rpi
      INNER JOIN tbl_customer c on c.id = rpi.cust_id
      INNER JOIN tbl_staff s on s.id = rpi.doctor_id
      INNER JOIN tbl_payment_method pm on pm.id = rpi.method_id
      WHERE rpi.receipt_id='$head_id' AND rpi.`status` = 1 "; 
    $QUERY1 = mysqli_query($CON, $SQL);   
    $total_all_rows = mysqli_num_rows($QUERY1);  
    $output = array();
    $columns = array(
      0 => 'id',
      1 => 'inv_code',
      2 => 'cust_fname',
      3 => 'staff_fname',
      4 => 'amount',
      5 => 'payment_method',
      6 => 'inv_date',
      7 => 'created_inv_by',
    );
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    }
    
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    }
    // 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0; 
    // 
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $uid = $ROW['created_inv_by'];
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$uid' ")); 
      $sub_array = array(); 
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>'; 
      $sub_array[] = '<center>'.$ROW['paid_code'].'</center>';
      $sub_array[] = '<center>'.$ROW['inv_code'].'</center>';
      $sub_array[] = $ROW['cust_fname'];
      $sub_array[] = $ROW['staff_fname'];
      $sub_array[] = '<center>$ '.$ROW['amount'].'</center>'; // amount
      $sub_array[] = '<center>'.$ROW['payment_method'].'</center>';// Method
      $sub_array[] = $USER['staff_fname'];
      $sub_array[] = '<center>'.$ROW['inv_date'].'</center>';//  
           
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
  // 
  function receivePayment(){
    global $CON; 
    $uid            = $_POST['uid'];
    $paid_id        = $_POST['paid_id']; 
    $entry_date     = $_POST['entry_date']; 
    $remark         = $_POST['remark']; 
    $post_date      = $_POST['post_date']; 
    $created_date   = date('Y-m-d H:i:s'); 
      
    //
    if ($paid_id == '') {
      $data = array('status' => 'false', 'header_id' => 0 );
    } else {
      // header
      $SQL = "INSERT INTO `tbl_receipt_payment_h` (`entry_date`, `post_date`, `created_date`, `created_by`, `remark`)
              VALUES (?, ?, ?, ?, ?)";  
      $stmt = mysqli_prepare($CON, $SQL); 
      mysqli_stmt_bind_param($stmt,'sssss', $entry_date, $post_date, $created_date, $uid, $remark);
      $QUERY = mysqli_stmt_execute($stmt); 
      $hid = mysqli_insert_id($CON);// return insert id
      // 
      $total_amount = 0; 
      $total_count = count($paid_id); 
      // loop
      foreach($paid_id as $pd) {
        $invObj = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `ip`.inv_title,`ip`.`timestamp`,ipt.inv_id,`ipt`.payment_amount,`ipt`.`paym_id`,`ip`.`cust_id`,`ip`.`user_id`,`ip`.`staff_id`,`ipt`.`code_number`
        FROM `tbl_invoice_patient` AS `ip` INNER JOIN `tbl_invoice_payment` AS `ipt` ON (`ipt`.`inv_id` = `ip`.`id`) where `ipt`.id = '$pd'"));
        $timestamp = date('Y-m-d', strtotime($invObj['timestamp']));  

        // insert item receive payment
        $SQL_i = "INSERT INTO `tbl_receipt_payment_item` (`receipt_id`,`cust_id`,`payment_id`,`method_id`, `inv_id`, `inv_code`, `inv_date`,`amount`,`created_inv_by`,`doctor_id`,`paid_code`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";  
        $stmt_i = mysqli_prepare($CON, $SQL_i);
        mysqli_stmt_bind_param($stmt_i,'sssssssssss', $hid, $invObj['cust_id'], $pd, $invObj['paym_id'], $invObj['inv_id'], $invObj["inv_title"], $timestamp, $invObj["payment_amount"],$invObj["user_id"],$invObj["staff_id"],$invObj["code_number"]);
        $qt = mysqli_stmt_execute($stmt_i); 

        // update invoice payment status of receive payment
        mysqli_query($CON, "UPDATE `tbl_invoice_payment` SET `is_received` = 1 WHERE `id` = '$pd'");
        // 
        $total_amount = $total_amount + $invObj["payment_amount"]; 
      }
      // update total h
      mysqli_query($CON, "UPDATE `tbl_receipt_payment_h` SET `total_amount` = $total_amount, `count_invoice` = $total_count WHERE `id` = '$hid'");
      //
      $data = array('status' => $QUERY, 'header_id' => $hid );
    }
    echo json_encode($data);
  }
  function getDataReport() {
    global $CON;
    $o = '';
		$date = $_POST['date'];
		$filter = $_POST['str'];
    $lang = $_POST['lang'];
		if ($filter == 'Custom Range') { $filter = 'Custom Date'; }
		$date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $title = ($lang == 1) ? array('Total Receipt Payment', 'Total Bank', 'Total Cash') : array('វិក្កយបត្រសរុប', 'បានទូទាត់រួច', 'មិនទាន់ទូទាត់');
    $url = array('report_invoice.php?pgid=22', 'invoice_completed.php?pgid=11', 'invoice_pending.php?pgid=10', 'invoice_draft.php?pgid=9');
		$img = array('payment', 'paid', 'pending', 'draft');
    $INCOME = mysqli_query($CON, "SELECT  case when paym_id = 1 then sum(payment_amount) ELSE 0 END AS cash, 
	                                                            case when paym_id != 1 then sum(payment_amount) ELSE 0 END AS bank  
                                                      FROM tbl_invoice_payment
                                                      WHERE (`timestamp` BETWEEN '$start' AND '$end')");  // 
    while ($ROW = mysqli_fetch_assoc($INCOME)) { 
      $cash = $cash+($ROW['cash']);
      $bank = $bank+($ROW['bank']); 
    }
		$data = array('<small>$ </small>'.shortNumber($cash+$bank), '<small>$ </small>'.shortNumber($cash), '<small>$ </small>'.shortNumber($bank));
    foreach ($title as $i => $t) { 
			$o .= '<div class="col-lg-4 col-md-3 col-6 mb-4">';
			$o .=   '<div class="card">';
			$o .=     '<div class="card-body">'; 
			$o .=       '<h1 class="card-title text-nowrap mb-2">'.$data[$i].'</h1>';
			$o .=       '<h5 class="mb-1">'.$title[$i].'</h5>';
			$o .=       '<small class="text-secondary fw-light">'.$filter.'</small>';
			$o .=     '</div>';
			$o .=   '</div>';
			$o .= '</div>';
    }
		echo $o;
  }
?>