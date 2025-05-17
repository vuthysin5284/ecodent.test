<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnTablePayment(); }
  else if ($qid == 3) { getRemainPayment(); }
  else if ($qid == 4) { fnInsert(); }
  else if ($qid == 5) { telegramNotify(); }
  else if ($qid == 6) { getDataRow(); }
  else if ($qid == 7) { fnUpdate(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `i`.`id` AS `invid`, `payment_amount`, `inv_grandtotal`, `inv_remain` FROM `tbl_invoice_payment` AS `ip` INNER JOIN `tbl_invoice_patient` AS `i` ON (`ip`.`inv_id` = `i`.`id`) WHERE `ip`.`id` = '$id' LIMIT 1"));
    $invid = $ROW['invid'];
    $payment = $ROW['payment_amount'];
    $grandtotal = $ROW['inv_grandtotal'];
    $remain = $ROW['inv_remain'];
    $new_remain = $payment + $remain;
    $new_payment = $grandtotal - $new_remain;
    $status = ($new_payment >= 0) ? 2 : 3;
    mysqli_query($CON, "UPDATE `tbl_invoice_patient` SET `inv_remain` = '$new_remain', `inv_status` = '$status' WHERE `id` = '$invid' LIMIT 1");
    mysqli_query($CON, "DELETE FROM `tbl_transaction` WHERE `payment_id` = '$id' LIMIT 1");
    $QUERY = mysqli_query($CON, "DELETE FROM `tbl_invoice_payment` WHERE `id` = '$id' LIMIT 1");
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $icode = $_POST['icode'];
    $uid = $_POST['uid'];
    $sql = "SELECT `service_description`, `tmt_price`, `tooth_id`, `tmt_discount`, `cust_id`, `cust_code`, `inv_discount`, `inv_discount_type` FROM `tbl_invoice_treatment` AS `ivtm` INNER JOIN `tbl_treatment_service` AS `ts` ON (`ivtm`.`tmsv_id` = `ts`.`id`) INNER JOIN `tbl_invoice_patient` AS `i` ON (`ivtm`.`inv_id` = `i`.`id`) INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) WHERE `inv_code` = '$icode'";
    $totalQuery = mysqli_query($CON, $sql);
    $total_all_rows = mysqli_num_rows($totalQuery);
    $output = array();
    $query = mysqli_query($CON, $sql);
    $count_rows = mysqli_num_rows($query);
    $data = array();
    $i = 0;
    $subtotal = 0;
    while ($row = mysqli_fetch_assoc($query)) {
      $tooth = explode(', ', $row['tooth_id']);
      $qty = count($tooth);
      $price = $row['tmt_price'];
      $discount = $row['tmt_discount'];
      $totaldiscount = (float) $row['inv_discount'];
      $inv_discount_type = $row['inv_discount_type'];
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
      $sub_array[] = '<span class="text-wrap">'.$row['service_description'].'</span>';
      $sub_array[] = '<center><span class="text-wrap">'.$row['tooth_id'].'</span></center>';
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
    $icode = $_POST['icode'];
    $uid = $_POST['uid'];
    $UID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $sql = "SELECT `i`.*, `inv_remain`, `payment_method` FROM `tbl_invoice_payment` AS `i` INNER JOIN `tbl_invoice_patient` AS `ip` ON (`i`.`inv_id` = `ip`.`id`) INNER JOIN `tbl_payment_method` AS `pm` ON (`i`.`paym_id` = `pm`.`id`) WHERE `inv_code` = '$icode' ORDER BY `i`.`id` ASC";
    $totalQuery = mysqli_query($CON, $sql);
    $total_all_rows = mysqli_num_rows($totalQuery);
    $output = array();
    $query = mysqli_query($CON, $sql);
    $count_rows = mysqli_num_rows($query);
    $data = array();
    $i = 0;
    while ($row = mysqli_fetch_assoc($query)) {
      $timestamp = date('d - M - Y', strtotime($row['timestamp'])).'<br>'.date('H : i A', strtotime($row['timestamp']));
      $amount = $row['payment_amount'];
      $remain = $row['inv_remain'];
      $btnShow = '<a href="print_invoice_patient.php?pgid='.$_POST['pgid'].'&icode='.$icode.'&pmid='.$row['id'].'" class="btn btn-icon btn-primary showBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><span class="tf-icons bx bx-printer"></span></a>';
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$row['id'].'"  class="btn btn-icon btn-warning editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Change payment method"><span class="tf-icons bx bx-edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$row['id'].'" class="btn btn-icon btn-danger deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      }
      
      $total = $total + $amount;
      $sub_array = array(); 
      $sub_array[] = '<center>'.$row['id'].'</center>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$row['payment_note'].'</center>';
      $sub_array[] = '<center>'.$row['payment_method'].'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($amount,2).'</span></div>';
      $sub_array[] = ($UID['staff_position_id'] == 1) ? '<center>'.$btnShow.' '.$btnEdit.'</center>' : '<center>'.$btnShow.'</center>'; //'.$btnDelete.'
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

  function getRemainPayment() {
    global $CON;
    $icode = $_POST['icode'];
    $SQL = "SELECT `inv_remain` FROM `tbl_invoice_patient` WHERE `inv_code` = '$icode' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnInsert() {
    global $CON;
    $icode = $_POST['icode'];
    $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `i`.`id`, `inv_remain`, `cust_fname` FROM `tbl_invoice_patient` AS `i` INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) WHERE `inv_code` = '$icode' LIMIT 1 "));
    $invid = $ROW['id'];
    $uid = $_POST['uid'];
    $paym = $_POST['paym'];
    $note = $_POST['note'];
    $datetime = $_POST['datetime'];
    $name = $ROW['cust_fname'];
    $toggle = $_POST['toggle'];
    $amount_en = (float) $_POST['amount_en'];
    $amount_kh = (float) $_POST['amount_kh']; 
    $remain = (float) $ROW['inv_remain'];
    $amount = $amount_en + ($amount_kh / $_SESSION['rating']);
    $total = $remain - $amount;
    if ($amount > $remain) {
      $payment = $remain;
      $change = $amount - $remain;
    } else {
      $payment = $amount;
      $change = 0;
    }

    $change_en = (float) $_POST['change_en'];
    $change_kh = (float) str_replace(",","",$_POST['change_kh']);

    $SQL = "INSERT INTO `tbl_invoice_payment` (`timestamp`, `inv_id`, `amount_en`, `amount_kh`,`change_en`, `change_kh`, `payment_amount`, `change_amount`, `paym_id`, `payment_note`, `user_id`) VALUES ('$datetime', '$invid', '$amount_en', '$amount_kh', '$change_en', '$change_kh','$payment', '$change', '$paym', '$note', '$uid')";
    $QUERY = mysqli_query($CON, $SQL);
    $pid = mysqli_insert_id($CON);
    if ($QUERY == TRUE) {
      if ($total > 0 ) {
        mysqli_query($CON, "UPDATE `tbl_invoice_patient` SET `inv_remain` = '$total' WHERE `id` = '$invid' LIMIT 1");
      } else {
        mysqli_query($CON, "UPDATE `tbl_invoice_patient` SET `inv_remain` = 0, `inv_status` = 3 WHERE `id` = '$invid' LIMIT 1");
      }
      mysqli_query($CON, "INSERT INTO `tbl_transaction` (`trans_id`, `payment_id`, `trans_status`) VALUES ('$invid', '$pid', 1)");
      $data = array('status' => 'true', 'pid' => $pid);
    } else { $data = array('status' => 'false'); }
    echo json_encode($data);
  }

  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $paym = $_POST['paym'];
    $note = $_POST['note'];
    $datetime = $_POST['datetime'];
    $SQL = "UPDATE `tbl_invoice_payment` SET `timestamp` = '$datetime', `paym_id` = '$paym', `payment_note` = '$note' WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function getDataRow() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "SELECT DATE_ADD(`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `payment_note`, `paym_id` FROM `tbl_invoice_payment` WHERE `id` = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function telegramNotify() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "SELECT `i`.`id`, `cust_fname`, `payment_amount`, `payment_method`, `inv_grandtotal`, `inv_remain` FROM `tbl_invoice_payment` AS `ip` INNER JOIN `tbl_invoice_patient` AS `i` ON (`ip`.`inv_id` = `i`.`id`) INNER JOIN `tbl_payment_method` AS `pm` ON (`ip`.`paym_id` = `pm`.`id`) INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) WHERE `ip`.`id` = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    $invoice = 'INV-'. sprintf('%05d', $ROW['id']);
    $name = $ROW['cust_fname'];
    $total = $ROW['inv_grandtotal'];
    $amount = $ROW['payment_amount'];
    $remain = $ROW['inv_remain'];
    $method = $ROW['payment_method'];
    $content = "📣 ដំណឹង ៖ អតិថិជនបានទូទាត់ប្រាក់រួចរាល់!\n\n";
    $content .= "🧾 វិក្កយបត្រ : ".$invoice."\n";
    $content .= "💁‍♂️ អតិថិជន : ".$name."\n";
    $content .= "👉 តម្លៃសរុប : <b>".number_format($total,2)." USD</b>\n";
    $content .= "👉 បានបង់ : <b>".number_format($amount,2)." USD</b>\n";
    $content .= "👉 នៅសល់ : <b>".number_format($remain,2)." USD</b>\n";
    $content .= "💳 តាមរយៈ : ".$method."\n";
    $content .= "🗓 កាលបរិច្ឆេទ : ".date('d-M-Y');
    $apiToken = "6930611083:AAER50bkN4cZrdKKmUyJnrVfauOg-NVxhpI";
    $data = [
      'chat_id' => '@dentystnotify67',
      'text' => $content,
      'parse_mode' => 'HTML'
    ];
    $response = file_get_contents("https://api.telegram.org/bot$apiToken/sendMessage?".http_build_query($data));
    echo json_encode($content);
  }
?>