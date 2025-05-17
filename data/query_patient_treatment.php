<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnInsert(); }
  else if ($qid == 3) { getPrice(); }
  else if ($qid == 4) { createInvoice(); }
  else { }
  
  function fnTable() {
    global $CON;
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];
    $tmpid = $_POST['tmpid']; 
    $is_invoice = $_POST['is_invoice']; 
    $SQL = "SELECT `tmt`.`id`, `service_description`, `service_price`, `tooth_id`, `tmt_price`, `tmt_discount`, `memb_discount` FROM `tbl_treatment` AS `tmt` INNER JOIN `tbl_treatment_plan` AS `tmpl` ON (`tmt`.`tmpl_id` = `tmpl`.`id`) INNER JOIN `tbl_treatment_service` AS `tmsv` ON (`tmt`.`tmsv_id` = `tmsv`.`id`) INNER JOIN `tbl_customer` AS `c` ON (`tmpl`.`cust_id` = `c`.`id`) INNER JOIN `tbl_membership` AS `m` ON (`c`.`memb_id` = `m`.`id`) WHERE (`cust_code`='$cid' AND `tmpl_id` = '$tmpid' AND `tmt_status` = 1)";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $tooth = explode(', ', $ROW['tooth_id']);
      $qty = count($tooth);
      $price = $ROW['tmt_price'];
      $discount = $ROW['tmt_discount'];
      $memb_discount = $ROW['memb_discount'];
      $total = (float) $qty * (float) $price;
      $total = $total - (($discount * $total) / 100);
      $subtotal = $subtotal + $total; 
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        if($is_invoice==0){
          $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></span></a>';
        }else{
          $btnDelete = "Only View";
        }
      }
      else{
        $btnDelete = "";
      }
      
      $sub_array = array(); 
      $sub_array[] = '<center><input class="form-check-input checkitem" type="checkbox" name="tmid[]" value="'.$ROW["id"].'" checked/><center>';
      $sub_array[] = '<span class="text-wrap">'.$ROW['service_description'].'</span>';
      $sub_array[] = '<center>'.$ROW['tooth_id'].'</center>';
      $sub_array[] = '<center>'.$qty.'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($price,2).'</span></div>';
      $sub_array[] = '<center>'.$discount.' %</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($total,2).'</span></div>';
      $sub_array[] = '<center>'.$btnDelete.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
      'subtotal' => $subtotal,
      'memb_discount' => $memb_discount,
    );
    echo  json_encode($output);
  }

  function getPrice() {
    global $CON;
    $svid = $_POST['svid'];
    $SQL = "SELECT `service_price` FROM `tbl_treatment_service` WHERE `id` = '$svid'";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnInsert() {
    global $CON;
    $uid = $_POST['uid'];
    $tmpId = $_POST['tmpid'];
    $tmsId = $_POST['service'];
    $tid = $_POST['tooth'];
    $disc = $_POST['discount'];
    $price = $_POST['price'];
    $status = 1;
    $TQ = mysqli_query($CON, "SELECT `id`, `tooth_description` FROM `tbl_tooth_item`");
    while ($TOOTH = mysqli_fetch_assoc($TQ)) {
      foreach($tid as $t) {
        $tooth .= ($TOOTH['id'] == $t) ? $TOOTH['tooth_description'].', ' : '';
      }
    }
    $tooth = rtrim($tooth, ', ');
    $SQL = "INSERT INTO `tbl_treatment` (`tmpl_id`, `tmsv_id`, `tooth_id`, `tmt_price`, `tmt_discount`, `user_id`, `tmt_status`) VALUES ('$tmpId', '$tmsId', '$tooth', '$price', '$disc', '$uid', '$status')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "DELETE FROM `tbl_treatment` WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status'=>'success') : array('status'=>'failed');
    echo json_encode($data);
  }

  function createInvoice() {
    global $CON;
    $custCode = $_POST['ccode'];
    $uid = $_POST['uid'];
    $tmid = $_POST['tmid'];
    $tmpid = $_POST['tmpid'];
    //
    if ($tmid == '') {
      $data = array('status' => 'false', 'ccode' => '', 'invid' => '' );
    } else {
      $CID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_customer` WHERE `cust_code` = '$custCode'"));
      $cid = $CID['id'];
      $invCode = $_POST['icode'];
      $discount = $_POST['totalDisc'];
      $type = $_POST['typeDisc'];
      $grandtotal = $_POST['grandtotal']; 
      $change_kh = $_POST['change_kh']; 
      $remain = 0;
      $status = 2;
      $INV = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_invoice_patient` ORDER BY `id` DESC LIMIT 1"));
      $inv_id = (int) $INV['id'] + 1;
      $INVNO = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `inv_title` FROM `tbl_invoice_patient` ORDER BY `inv_title` DESC LIMIT 1"));
      $inv_title = $INVNO['inv_title'];
      $title = explode('-', $inv_title);
      $year = $title[0];
      $inv_no = (int) $title[1] + 1;
      $invoice = $year.'-'.sprintf('%04d', $inv_no);
      $SQL = "INSERT INTO `tbl_invoice_patient` (`id`, `inv_code`, `inv_title`, `cust_id`, `inv_discount`, `inv_discount_type`, `inv_grandtotal`, `inv_remain`, `change_kh`, `staff_id`, `user_id`, `inv_status`, `share_status`) VALUES ('$inv_id', '$invCode', '$invoice', '$cid', '$discount', '$type', '$grandtotal', '$grandtotal', '$change_kh', '$uid', '$uid', '$status', 0)";
      $QUERY = mysqli_query($CON, $SQL);
      foreach($tmid as $t) {
        $TMID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `tmsv_id`, `tooth_id`, `tmt_price`, `tmt_discount` FROM `tbl_treatment` WHERE `id` = '$t'"));
        $ttsv = $TMID['tmsv_id'];
        $ttid = $TMID['tooth_id'];
        $tooth = explode(', ', $ttid);
        $qty = count($tooth);
        $ttdis = $TMID['tmt_discount'];
        $ttprice = $TMID['tmt_price'];
        $IVTM = mysqli_query($CON, "INSERT INTO `tbl_invoice_treatment` (`inv_id`, `tmsv_id`, `tooth_qty`, `tooth_id`, `tmt_price`, `tmt_discount`) VALUES ('$inv_id', '$ttsv', '$qty', '$ttid', '$ttprice', '$ttdis')");
      }
      $NOTID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_notification` WHERE `cust_id` = '$cid' ORDER BY `timestamp` DESC LIMIT 1"));
      $notid = $NOTID['id'];
      $NOTIFICATION = mysqli_query($CON, "UPDATE `tbl_notification` SET `notify_id` = 4, `user_id` = 1 WHERE `id` = '$notid' LIMIT 1");
      // update treatment plan is issued invoice
      mysqli_query($CON, "UPDATE `tbl_treatment_plan` SET  `is_invoice` = 1 WHERE `id` = '$tmpid' ");

      $data = array('status' => 'true', 'ccode' => $custCode, 'invid' => $inv_id );
    }
    echo json_encode($data);
  }
?>