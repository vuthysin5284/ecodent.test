<?php
	include_once ('../inc/config.php');
	include_once ('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnShare(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_invoice_patient` SET `share_amount` = 0, `share_status` = 0 WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }
  
  function fnShare() {
    global $CON;
    $id = $_POST['id'];
    $share = $_POST['share'];
    $status = $_POST['status'];
    $SQL = "UPDATE `tbl_invoice_patient` SET `share_amount` = '$share', `share_status` = '$status' WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
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
    $SQL = "SELECT `i`.`id`, DATE_ADD(`i`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `inv_code`, `inv_title`, `inv_discount`, `inv_discount_type`, `inv_grandtotal`, `inv_remain`, `i`.`cust_id`, `cust_fname`, `cust_image`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `i`.`staff_id`, `staff_fname`, `staff_commission`, `share_amount`, `share_status` FROM `tbl_invoice_patient` AS `i` INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`i`.`staff_id` = `s`.`id`) WHERE (`i`.`timestamp` BETWEEN '$start' AND '$end')";
    $output = array();
    $columns = array(
      1 => 'cust_id',
      2 => 'cust_fname',
      3 => 'inv_title',
      4 => 'inv_grandtotal',
      5 => 'inv_remain',
      6 => 'share_status',
      7 => 'staff_id',
      8 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `i`.`cust_id` LIKE '%".$search_value."%')";
    }
    if ($_POST['sid'] != '') {
      $sid = $_POST['sid'];
      $SQL .= " AND `i`.`staff_id` = '$sid'";
    }
    if ($_POST['zid'] != '') {
      $zid = $_POST['zid'];
      $SQL .= " AND `share_status` = '$zid'";
    }
    $SQL .= " AND `inv_status` > 1 GROUP BY `i`.`id`";
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
    $grandTotal = 0;
    $shareTotal = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $id = $ROW['id'];
      $commission = (float) $ROW['staff_commission'];
      $payment = (float) $ROW['inv_grandtotal'];
      $inv_discount = (float) $ROW['inv_discount'];
      $inv_remain = (float) $ROW['inv_remain'];
      $shared_amount = (float) $ROW['share_amount'];
      $inv_discount_type = $ROW['inv_discount_type'];
      $SHARE_PART = mysqli_query($CON, "SELECT `tmt_price`, `service_cost`, `tooth_id`, `tmt_discount` FROM `tbl_invoice_patient` AS `i` INNER JOIN `tbl_invoice_treatment` AS `it` ON (`it`.`inv_id` = `i`.`id`) INNER JOIN `tbl_treatment_service` AS `ts` ON (`it`.`tmsv_id` = `ts`.`id`) WHERE `i`.`id` = '$id'");
      $subtotal = 0;
      $grandtotal = 0;
      while ($SP = mysqli_fetch_assoc($SHARE_PART)) {
        $tooth = explode(', ', $SP['tooth_id']);
        $qty = count($tooth);
        $cost = (float) $SP['service_cost'];
        $price = (float) $SP['tmt_price'];
        $discount = (float) $SP['tmt_discount'];
        $total = $qty * $price;
        $grandtotal = $grandtotal + $total;
        $newtotal = $total - (($discount * $total) / 100);
        $pretotal = $newtotal - (($cost * $newtotal) / 100);
        $subtotal = $subtotal + $pretotal;
      }
      if ($inv_discount_type == 0) {
        $newsubtotal = $subtotal - (($inv_discount * $subtotal) / 100);
      } else {
        $inv_discount = ($inv_discount * 100) / $grandtotal;
        $newsubtotal = $subtotal - (($inv_discount * $subtotal) / 100);
      }

      $share = ($newsubtotal - $inv_remain);
      $totalshare = ($commission * $newsubtotal) / 100 ;
      $shareAmount = (($totalshare * $share) / $newsubtotal) - $shared_amount;
      $shareTotal = $shareTotal + $shareAmount;
      $grandTotal = $grandTotal + $payment;

      $totalShared = $shared_amount + $shareAmount;

      if ($shared_amount == 0) { $status = 0; }
      else if ($shared_amount < $totalshare) { $status = 1; }
      else { $status = 2; }
      
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);
      $invid = 'INV-'. sprintf('%05d', $ROW['id']);
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $btnShare = '<a href="javascript:void();" data-id="'.$ROW['id'].'" data-share="'.$totalShared.'" data-status="'.$status.'" class="btn btn-icon btn-primary mb-2 shareBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Share"><span class="tf-icons bx bx-check"></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger mb-2 deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-x"></a>';
      if ($ROW['share_status'] == 0) { $badge = '<span class="badge bg-label-warning p-2">PENDING</span>'; }
      else if ($ROW['share_status'] == 1) { $badge = '<span class="badge bg-label-primary p-2">PARTIAL</span>'; }
      else { $badge = '<span class="badge bg-label-success p-2">SHARED</span>'; }
      $sub_array = array();
      $sub_array[] = '<center><a href="../page/invoice_payment.php?icode='.$ROW["inv_code"].'">'.$invid.'</a></center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="50" width="50"></center>';
      $sub_array[] = $ROW['cust_fname'];
      $sub_array[] = $ROW['inv_title'];
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($payment,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($shareAmount,2).'</span></div>';
      $sub_array[] = '<center>'.$badge.'</center>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = ($ROW['share_status'] == 2) ? '<center>'.$btnDelete.'</center>' : '<center>'.$btnShare.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
      'grandTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($grandTotal,2).'</span></div>',
      'shareTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($shareTotal,2).'</span></div>',
    );
    echo json_encode($output);
  }
?>