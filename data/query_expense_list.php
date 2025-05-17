<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow('tbl_invoice_expense'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else if ($qid == 6) { getSupplier(); }
  else { }

  function fnDelete() {
    global $CON;
		$id = $_POST['id'];
    $SQL = "UPDATE `tbl_invoice_expense` SET `exp_status` = 0 WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
	}

	function fnTable() {
    global $CON;
		$SQL = "SELECT `e`.*, `prod_category` FROM `tbl_invoice_expense` AS `e` INNER JOIN `tbl_product_category` AS `c` ON (`e`.`exp_cate_id` = `c`.`id`) WHERE `exp_status` > 0";
    $totalQuery = mysqli_query($CON, $SQL);
    $total_all_rows = mysqli_num_rows($totalQuery);
    $output = array();
    $columns = array(
      1 => 'id',
      2 => 'exp_description',
      3 => 'exp_cate_id',
      4 => 'supp_id',
      5 => 'exp_amount',
      6 => 'exp_remain',
      7 => 'exp_status',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`exp_description` LIKE '%". $search_value ."%'";
      $SQL .= " OR `e`.`id` LIKE '%".$search_value."%')";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `e`.`timestamp` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $uid = $ROW['user_id'];
      $sid = $ROW['supp_id'];
      if ($ROW['exp_cate_id'] == 1) {
        $SUPPLIER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` AS `name` FROM `tbl_staff` WHERE `id` = '$sid' LIMIT 1"));
      } else {
        $SUPPLIER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `supp_fname` AS `name` FROM `tbl_supplier` WHERE `id` = '$sid' LIMIT 1"));
      }
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
      $btnShow = '<a href="expense_payment.php?pgid=17&excode='.$ROW['exp_code'].'" class="btn btn-icon btn-primary mb-2 showBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-receipt"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger mb-2 deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
			$status = ($ROW['exp_status'] == 1) ? '<span class="badge bg-label-primary p-2">PENDING</span>' : '<span class="badge bg-label-success p-2">PAID</span>';
      $amount = $ROW['exp_amount'];
      $remain = $ROW['exp_remain'];
      $supplier = ($ROW['exp_cate_id'] == 1) ? $ROW['staff_fname'] : $ROW['supp_fname'];
      $sub_array = array();
      $sub_array[] = '<center>'.$i = ($i + 1).'</center>';
      $sub_array[] = $ROW['exp_description'];
			$sub_array[] = '<center>'.$ROW['prod_category'].'</center>';
			$sub_array[] = '<center>'.$SUPPLIER['name'].'</center>';
			$sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($amount,2).'</span></div>';
			$sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($remain,2).'</span></div>';
			$sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] = '<center>'.$btnShow.' '.$btnDelete.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw'=> intval($_POST['draw']),
      'recordsTotal' =>$count_rows ,
      'recordsFiltered'=>   $total_all_rows,
      'data'=>$data,
    );
    echo  json_encode($output);
	}

	function fnInsert() {
    global $CON;
		$id = $_POST['id'];
    $code = $_POST['code'];
		$description = $_POST['description'];
		$categ = $_POST['categ'];
		$suppid = $_POST['supplier'];
		$amount = $_POST['amount'];
		$uid = $_POST['uid'];
		$status = 1;
		$SQL = "INSERT INTO `tbl_invoice_expense` (`exp_code`, `exp_description`, `exp_cate_id`, `supp_id`, `exp_amount`, `exp_remain`, `user_id`, `exp_status`) VALUES ('$code', '$description', '$categ', '$suppid', '$amount', '$amount', '$uid', '$status')";
		$QUERY = mysqli_query($CON, $SQL);
		$data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
		echo json_encode($data);
	}

	function fnUpdate() {
    global $CON;
    $sid = $_POST['id'];
    $id = trim($sid, 'EXP-');
    $id = (int) $id;
    $code = $_POST['code'];
		$description = $_POST['description'];
		$categ = $_POST['categ'];
		$suppid = $_POST['supplier'];
		$amount = $_POST['amount'];
		$uid = $_POST['uid'];
		$remain = $amount - $payment;
		$status = ($remain > 0) ? 1 : 2;
		$SQL = "UPDATE `tbl_invoice_expense` SET `exp_description` = '$description', `exp_cate_id` = '$categ', `supp_id` = '$suppid', `exp_amount` = '$amount' WHERE `id` = '$id'";
		$QUERY = mysqli_query($CON, $SQL);
		$data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
		echo json_encode($data);}

	function getLastId() {
    global $CON;
		$SQL = "SELECT `id` FROM `tbl_invoice_expense` ORDER BY `id` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
	}

  function getSupplier() {
    global $CON;
    $str = $_POST['str'];
    if ($str == 1) {
      $SQL = "SELECT `id`, `staff_fname` AS `name` FROM `tbl_staff` WHERE `id` > 1 AND `staff_status` = 1";
    } else {
      $SQL = "SELECT `id`, `supp_fname` AS `name` FROM `tbl_supplier` WHERE `exp_cate_id` = '$str'";
    }
    $QUERY = mysqli_query($CON, $SQL);
    $o = '';
    $o .= '<option hidden value="">--- select item ---</option>';
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
	    $o .= '<option value="'.$ROW['id'].'">'.$ROW['name'].'</option>';
    }
    echo $o;
  }
?>
