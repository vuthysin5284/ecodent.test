<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];

  if ($qid == 1) { getData(); } else if ($qid == 2) { fnTable(); } else { }

  function fnTable() {
    global $CON;
    $icode = $_POST['icode'];
    $SQL = "SELECT `service_description`, `tmt_price`, `tooth_id`, `tmt_discount`, `cust_id`, `cust_code`, `inv_discount`, `inv_discount_type` FROM `tbl_invoice_treatment` AS `ivtm` INNER JOIN `tbl_treatment_service` AS `ts` ON (`ivtm`.`tmsv_id` = `ts`.`id`) INNER JOIN `tbl_invoice_patient` AS `i` ON (`ivtm`.`inv_id` = `i`.`id`) INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) WHERE `inv_code` = '$icode'";
    $QUERY = mysqli_query($CON, $SQL);
    $o = '';
    $i = 0;
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

        $o .= '<tr>';
        $o .= '<td class="text-center text-dark">'.($i += 1).'</td>';
        $o .= '<td class="text-wrap text-dark">'.$ROW['service_description'].'</td>';
        $o .= '<td class="text-center text-dark"><span class="text-wrap">'.$ROW['tooth_id'].'</span></td>';
        $o .= '<td class="text-center text-dark">'.$qty.'</td>';
        $o .= '<td><div class="d-flex justify-content-between p-0 text-dark"><span>$</span><span>'.number_format($price,2).'</span></div></td>';
        $o .= '<td class="text-center text-dark">'.$discount.'<small>%</small></td>';
        $o .= '<td><div class="d-flex justify-content-between p-0 text-dark"><span>$</span><span>'.number_format($total,2).'</span></div></td>';
        $o .= '</tr>';
    }

    $o .= '<tr>';
    $o .= '<td colspan="6" class="fw-bolder text-dark" style="text-align : right">តម្លៃសរុប</td>';
    $o .= '<td><div class="d-flex fw-bolder justify-content-between p-0 text-dark"><span>$</span><span>'.number_format($subtotal,2).'</span></div></td>';
    $o .= '</tr>';

    $o .= '<tr>';
    $o .= '<td colspan="6" class="fw-bolder text-dark" style="text-align : right">បញ្ចុះតម្លៃ</td>';
    $o .= '<td><div class="d-flex fw-bolder justify-content-between p-0 text-dark"><span>'.$type.'</span><span>'.number_format($totaldiscount,2).'</span></div></td>';
    $o .= '</tr>';

    $o .= '<tr>';
    $o .= '<td colspan="6" class="fw-bolder text-dark" style="text-align : right">សរុប</td>';
    $o .= '<td><div class="d-flex fw-bolder justify-content-between p-0 text-dark"><span>$</span><span>'.number_format($grandtotal,2).'</span></div></td>';
    $o .= '</tr>';
    echo $o;
  }

  function getData() {
    global $CON;
    $icode = $_POST['icode'];
    $SQL = "SELECT `i`.`id`, `inv_title`, `cust_id`, `cust_fname`, `cust_contact`, `cust_address`, `cust_gender`, `cust_dob` FROM `tbl_customer` AS `c` INNER JOIN `tbl_invoice_patient` AS `i` ON (`i`.`cust_id` = `c`.`id`) WHERE `i`.`inv_code` = '$icode' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $o = '';
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $name = $ROW['cust_fname'];
      $contact = $ROW['cust_contact'];
      $address = $ROW['cust_address'];
      $invoice = $ROW['inv_title'];
      $inv_id = 'INV-'. sprintf('%05d', $ROW['id']);
      $cust_id = 'P-'. sprintf('%05d', $ROW['cust_id']);
      $gender = ($ROW['cust_gender'] == 0) ? 'ស្រី' : 'ប្រុស';
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
    
      $o .= '<div class="row align-items-top mb-3">';
      $o .= '<div class="col-5 text-body text-dark">លេខកូដ / PID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.$cust_id.'</strong></div>';
      $o .= '<div class="col text-body text-dark">វិក្ក័យបត្រ / Invoice No &nbsp; : &nbsp; <strong class="text-dark">'.$invoice.'</strong></div>';
      $o .= '</div>';
      $o .= '<div class="row align-items-top mb-3">';
      $o .= '<div class="col-5 text-body text-dark">អតិថិជន / Patient &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.$name.'</strong></div>';
      $o .= '<div class="col text-body text-dark">អាយុ / Age &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.$age.' ឆ្នាំ</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ភេទ / Sex &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.$gender.'</strong></div>';
      $o .= '</div>';
      $o .= '<div class="row align-items-top mb-3">';
      $o .= '<div class="col-5 text-body text-dark">លេខទូរស័ព្ទ / Contact &nbsp; : &nbsp; <strong class="text-dark">'.$contact.'</strong></div>';
      $o .= '<div class="col text-body text-dark">អាស័យដ្ឋាន / Address &nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.$address.'</strong></div>';
      $o .= '</div>';
      $o .= '';
    }
    echo $o;
  }

?>