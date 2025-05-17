<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];

  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnTablePayment(); }
  else if ($qid == 3) { getRemainPayment(); }
  else if ($qid == 4) { fnInsert(); }
  else if ($qid == 5) { getJSRow('tbl_invoice_expense'); }
  else if ($qid == 6) { fnUpdate(); }
  else if ($qid == 7) { getImage(); }
  else if ($qid == 8) { fnInsertImage(); }
  else if ($qid == 9) { fnDeleteImage(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `i`.`id` AS `expid`, `exp_payment_amount`, `exp_amount`, `exp_remain` FROM `tbl_expense_payment` AS `exp` INNER JOIN `tbl_invoice_expense` AS `i` ON (`exp`.`exp_id` = `i`.`id`) WHERE `exp`.`id` = '$id' LIMIT 1"));
    $expid = $ROW['expid'];
    $payment = $ROW['exp_payment_amount'];
    $grandtotal = $ROW['exp_amount'];
    $remain = $ROW['exp_remain'];
    $new_remain = $payment + $remain;
    $new_payment = $grandtotal - $new_remain;
    $status = ($new_payment >= 0) ? 1 : 2;
    mysqli_query($CON, "UPDATE `tbl_invoice_expense` SET `exp_remain` = '$new_remain', `exp_status` = '$status' WHERE `id` = '$expid'");
    mysqli_query($CON, "DELETE FROM `tbl_transaction` WHERE `payment_id` = '$id' LIMIT 1");
    $QUERY = mysqli_query($CON, "DELETE FROM `tbl_expense_payment` WHERE `id` = '$id'");
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $excode = $_POST['excode'];
    $SQL = "SELECT `e`.*, `prod_category` FROM `tbl_invoice_expense` AS `e` INNER JOIN `tbl_product_category` AS `c` ON (`e`.`exp_cate_id` = `c`.`id`) WHERE `exp_code` = '$excode'";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    $subtotal = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $sid = $ROW['supp_id'];
      if ($ROW['exp_cate_id'] == 1) {
        $SUP = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` AS `supplier` FROM `tbl_staff` WHERE `id` = '$sid' LIMIT 1"));
      } else {
        $SUP = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `supp_fname` AS `supplier` FROM `tbl_supplier` WHERE `id` = '$sid' LIMIT 1"));
      }
      $supplier = $SUP['supplier'];
      $amount = $ROW['exp_amount'];
      $remain = $ROW['exp_remain'];
      $payment = $amount - $remain;
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $status = ($ROW['exp_status'] == 1) ? '<span class="badge bg-label-primary p-2">PENDING</span>' : '<span class="badge bg-label-success p-2">PAID</span>';
      $sub_array = array();
      $sub_array[] = '<center>'.$i = ($i + 1).'</center>';
      $sub_array[] = '<span class="text-wrap">'.$ROW['exp_description'].'</span>';
      $sub_array[] = '<center>'.$ROW['prod_category'].'</center>';
      $sub_array[] = '<center>'.$supplier.'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between fw-bolder p-0"><span>$</span><span>'.number_format($amount,2).'</span></div>';
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

  function fnUpdate() {
    global $CON;
    $id = $_POST['exp_id'];
    $description = $_POST['description'];
    $amount = $_POST['total_amount'];
    $SQL = "UPDATE `tbl_invoice_expense` SET `exp_description` = '$description', `exp_amount` = '$amount' WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function getImage() {
    global $CON;
    $excode = $_POST['excode'];
    $EXP = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_invoice_expense` WHERE `exp_code` = '$excode' LIMIT 1"));
    $id = $EXP['id'];
    $folder = 'EXP-'. sprintf('%05d', $id).'/';
    $SQL = "SELECT * FROM `tbl_expense_image` WHERE `exp_id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $o = '';
    if (mysqli_num_rows($QUERY) > 0) {
      while ($ROW = mysqli_fetch_assoc($QUERY)) {
        $img = $ROW['exp_image'];
        $o .= '<div class="col-md-1 col-4 position-relative" z-index="100">';
        $o .=   '<a href="../images/profiles/'.$folder.''.$img.'.jpg" data-lightbox="patient-img"><img src="../images/profiles/'.$folder.''.$img.'.jpg" class="img-thumbnail d-block rounded mb-2 p-0" style="width: 100%; height:100px; object-fit: cover;"></a>';
        $o .=   '<div class="position-absolute top-0 end-0 mt-1 me-1">';
        $o .=     '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger p-0 deleteImage"><i class="bx bx-trash"></i></a>';
        $o .=   '</div>';
        $o .= '</div>';
      }
    echo $o;
    }
  }

  function fnInsertImage() {
    global $CON;
    $excode = $_POST['excode'];
    $EXP = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_invoice_expense` WHERE `exp_code` = '$excode' LIMIT 1"));
    $id = $EXP['id'];
    $fid = 'EXP-'. sprintf('%05d', $id);
    if (array_sum($_FILES['files']['size']) > 0) {
      foreach ($_FILES['files']['tmp_name'] as $key => $value ) {
        $file_type = $_FILES['files']['type'][$key];
        $allowed_type = "image/jpeg, jpg, gif, image/png";
        $allowed_path = explode(", ", $allowed_type);
        if (in_array($file_type, $allowed_path)) {
          $uploadDir = '../images/profiles/'.$fid.'/';
          $handle = new Upload($_FILES['files']['tmp_name'][$key]);
          $fileName = date('sihjmY').$key;
          $handle->image_resize          = true;
          $handle->image_ratio_x         = true;
          $handle->image_y               = 1080;
          $handle->image_x               = 720;
          $handle->jpeg_quality          = 72;
          $handle->image_text_background = 2;
          $handle->file_new_name_ext     = 'jpg';
          $handle->file_new_name_body    = $fileName;
          $handle->Process($uploadDir); 
          $SQL = "INSERT INTO `tbl_expense_image` (`exp_image`, `exp_id`) VALUES ('$fileName', '$id')";
          $QUERY = mysqli_query($CON, $SQL);
          $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
        }
      }
    }
    echo json_encode($data);
  }

  function fnDeleteImage() {
    global $CON;
    $id = $_POST['id'];
    $IMG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_expense_image` WHERE `id` = '$id'"));
    $fileName = $IMG['exp_image'];
    $folder = 'EXP-'. sprintf('%05d', $IMG['exp_id']).'/';
    $SQL = "DELETE FROM `tbl_expense_image` WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    if ($delQuery == TRUE) {
      $data = array('status' => 'success');
      $image_path = '../images/profiles/'.$folder.''.$fileName.'.jpg';
      @unlink($image_path);
    } else {
      $data = array('status' => 'failed');
    }
    echo json_encode($data);
  }

  function fnTablePayment() {
    global $CON;
    $excode = $_POST['excode'];
    $uid = $_POST['uid'];
    $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $SQL = "SELECT `i`.*, `exp_remain`, `payment_method` FROM `tbl_expense_payment` AS `i` INNER JOIN `tbl_invoice_expense` AS `ip` ON (`i`.`exp_id` = `ip`.`id`) INNER JOIN `tbl_payment_method` AS `pm` ON (`i`.`paym_id` = `pm`.`id`) WHERE `exp_code` = '$excode' ORDER BY `i`.`timestamp` ASC";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $amount = $ROW['exp_payment_amount'];
      $remain = $ROW['exp_remain'];
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      $btnShow = '<a href="print_expense_payment.php?pgid='.$_POST['pgid'].'&icode='.$icode.'&pmid='.$row['id'].'" class="btn btn-icon btn-primary showBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><span class="tf-icons bx bx-printer"></span></a>';
      // edit
      $total = $total + $amount;
      $sub_array = array(); 
      $sub_array[] = '<center>'.($i += 1).'</center>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$ROW['payment_note'].'</center>';
      $sub_array[] = '<center>'.$ROW['payment_method'].'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($amount,2).'</span></div>';
      $sub_array[] = ($USER['staff_position_id'] == 1) ? '<center>'.$btnShow.' '.$btnDelete.'</center>' : '';
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
    echo  json_encode($output);
  }

  function getRemainPayment() {
    global $CON;
    $excode = $_POST['excode'];
    $SQL = "SELECT `exp_remain` FROM `tbl_invoice_expense` WHERE `exp_code` = '$excode' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnInsert() {
    global $CON;
    $excode = $_POST['excode'];
    $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id`, `exp_remain`, `exp_cate_id` FROM `tbl_invoice_expense` WHERE `exp_code` = '$excode' LIMIT 1"));
    $expid = $ROW['id'];
    $uid = $_POST['uid'];
    $paym = $_POST['paym'];
    $note = $_POST['note'];
    $category = $ROW['exp_cate_id'];
    $amount = (float) $_POST['amount'];
    $remain = (float) $ROW['exp_remain'];
    $total = $remain - $amount;
    $SQL = "INSERT INTO `tbl_expense_payment` (`exp_id`, `exp_payment_amount`, `paym_id`, `payment_note`, `user_id`) VALUES ('$expid', '$amount', '$paym', '$note', '$uid')";
    $QUERY = mysqli_query($CON, $SQL);
    $pid = mysqli_insert_id($CON);
    if ($QUERY == TRUE) {
      if ($total > 0 ) {
        mysqli_query($CON, "UPDATE `tbl_invoice_expense` SET `exp_remain` = '$total' WHERE `id` = '$expid' LIMIT 1");
      } else {
        mysqli_query($CON, "UPDATE `tbl_invoice_expense` SET `exp_remain` = 0, `exp_status` = 2 WHERE `id` = '$expid' LIMIT 1");
      }
      mysqli_query($CON, "INSERT INTO `tbl_transaction` (`trans_id`, `payment_id`, `trans_status`) VALUES ('$expid', '$pid', 2)");
      $data = array('status' => 'true');
    } else { $data = array('status' => 'false'); }
    echo json_encode($data);
  }
?>