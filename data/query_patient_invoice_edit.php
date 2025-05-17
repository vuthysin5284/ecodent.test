<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnInsert(); }
  else if ($qid == 3) { getPrice(); }
  else if ($qid == 4) { createInvoice(); }
  else if ($qid == 5) { fnUpdate(); }
  else { }
  
  function fnTable() {
    global $CON;
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];
    $invid = $_POST['invid'];
    $SQL = "SELECT `service_description`, `service_price`, `tooth_id`, `tmt_discount`, `cust_id`, `cust_code`, `inv_discount`, `inv_discount_type` FROM `tbl_invoice_treatment` AS `ivtm` INNER JOIN `tbl_treatment_service` AS `ts` ON (`ivtm`.`tmsv_id` = `ts`.`id`) INNER JOIN `tbl_invoice_patient` AS `i` ON (`ivtm`.`inv_id` = `i`.`id`) INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) WHERE `cust_code`='$cid' AND `inv_id` = '$invid'";
    $totalQuery = mysqli_query($CON, $SQL);
    $total_all_rows = mysqli_num_rows($totalQuery);
    $output = array();
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    $subtotal = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $tooth = explode(', ', $ROW['tooth_id']);
      $qty = count($tooth);
      $price = $ROW['service_price'];
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

  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $title = $_POST['title'];
    $status = $_POST['status'];
    $grandtotal = $_POST['grandtotal'];
    $SQL = "UPDATE `tbl_invoice_patient` SET `inv_title` = '$title', `inv_status` = '$status', `inv_grandtotal` = '$grandtotal' WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
?>