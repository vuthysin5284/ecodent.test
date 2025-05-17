<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");

  $qid = $_POST['qid'];
  if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnTablePayment(); }
  else if ($qid == 3) { getData(); }
  else if ($qid == 4) { fnUpdate(); }
  else { }
  
  function fnTable() {
    global $CON;
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];
    $invid = $_POST['invid'];
    $SQL = "SELECT `service_description`, `tmt_price`, `tooth_id`, `tmt_discount`, `cust_id`, `cust_code`, `inv_discount`, `inv_discount_type` FROM `tbl_invoice_treatment` AS `ivtm` INNER JOIN `tbl_treatment_service` AS `ts` ON (`ivtm`.`tmsv_id` = `ts`.`id`) INNER JOIN `tbl_invoice_patient` AS `i` ON (`ivtm`.`inv_id` = `i`.`id`) INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) WHERE `cust_code` = '$cid' AND `inv_id` = '$invid'";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    $subtotal = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $tooth = explode(', ', $ROW['tooth_id']);
      $qty = count($tooth);
      $price = $ROW['tmt_price'];
      $discount = $ROW['tmt_discount'];
      $totaldiscount = (float) $ROW['inv_discount'];
      $inv_discount_type = $ROW['inv_discount_type'];
      $total = (float) $qty * (float) $price;
      $total = $total - (($discount * $total) / 100);
      $subtotal = $subtotal + $total;
      if ($inv_discount_type == 1 ) {
        $grandtotal = $subtotal - $totaldiscount;
      } else {
        $grandtotal = $subtotal - (($totaldiscount * $subtotal) / 100);
      }
      $type = ( $inv_discount_type == 1) ? '$' : '%';
      $inv_discount = '<div class="d-flex justify-content-between p-0"><span>'.$type.'</span><span>'.number_format($totaldiscount,2).'</span></div>';
      $inv_subtotal = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($subtotal,2).'</span></div>';
      $sub_array = array(); 
      $sub_array[] = '<center>'.($i += 1).'<center>';
      $sub_array[] = '<span class="text-wrap">'.$ROW['service_description'].'</span>';
      $sub_array[] = '<center><span class="text-wrap">'.$ROW['tooth_id'].'</span></center>';
      $sub_array[] = '<center>'.$qty.'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($price,2).'</span></div>';
      $sub_array[] = '<center>'.$discount.'<small>%</small></center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($total,2).'</span></div>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
      'subtotal' => $inv_subtotal,
      'totaldisc' => $inv_discount,
      'grandtotal' => number_format($grandtotal,2),
    );
    echo  json_encode($output);
  }

  function fnTablePayment() {
    global $CON;
    $cid = $_POST['cid'];
    $invid = $_POST['invid'];
    $uid = $_POST['uid'];
    $UID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $SQL = "SELECT `i`.*, `inv_remain`, `payment_method`, `inv_code` FROM `tbl_invoice_payment` AS `i` INNER JOIN `tbl_invoice_patient` AS `ip` ON (`i`.`inv_id` = `ip`.`id`) INNER JOIN `tbl_payment_method` AS `pm` ON (`i`.`paym_id` = `pm`.`id`) INNER JOIN `tbl_customer` AS `c` ON (`ip`.`cust_id` = `c`.`id`) WHERE `cust_code` = '$cid' AND `i`.`inv_id` = '$invid'";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $amount = $ROW['payment_amount'];
      $remain = $ROW['inv_remain'];
      $btnShow = '<a href="print_invoice_patient.php?icode='.$ROW['inv_code'].'&pmid='.$ROW['id'].'" class="btn btn-icon btn-primary showBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><span class="tf-icons bx bx-printer"></span></a>';
      $total = $total + $amount;
      $sub_array = array(); 
      $sub_array[] = '<center>'.($i += 1).'</center>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$ROW['payment_note'].'</center>';
      $sub_array[] = '<center>'.$ROW['payment_method'].'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($amount,2).'</span></div>';
      $sub_array[] = '<center>'.$btnShow.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
      'total' => number_format($total,2),
      'remain' => number_format($remain,2),
    );
    echo json_encode($output);
  }

  function getData() {
    global $CON;
    $invid = $_POST['invid'];
    $SQL = "SELECT * FROM `tbl_invoice_patient` WHERE `id` = '$invid' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $title = $_POST['title'];
    $status = $_POST['status'];
    $dentist = $_POST['dentist'];
    $grandtotal = $_POST['grandtotal'];
    $grandtotal = str_replace(',', '', $grandtotal);
    if ($status == 2) {
      $NOTID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `n`.`id` FROM `tbl_notification` AS `n` INNER JOIN `tbl_invoice_patient` AS `i` ON (`n`.`cust_id` = `i`.`cust_id`) WHERE `i`.`id` = '$id' ORDER BY `n`.`timestamp` DESC LIMIT 1"));
      $notid = $NOTID['id'];
      $NOTIFICATION = mysqli_query($CON, "UPDATE `tbl_notification` SET `notify_id` = 4, `user_id` = 1 WHERE `id` = '$notid' LIMIT 1");
    }
    $SQL = "UPDATE `tbl_invoice_patient` SET `inv_title` = '$title', `inv_status` = '$status', `inv_grandtotal` = '$grandtotal', `inv_remain` = '$grandtotal', `user_id` = '$dentist' WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

?>