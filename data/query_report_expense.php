<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  
  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getDataReport(); }
  else { }

  function fnTable() {
    global $CON;
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT `e`.*, `prod_category`, `staff_fname` FROM `tbl_invoice_expense` AS `e` INNER JOIN `tbl_product_category` AS `c` ON (`e`.`exp_cate_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`e`.`user_id` = `s`.`id`) WHERE (`e`.`timestamp` BETWEEN '$start' AND '$end') AND `exp_status` > 0";
    $output = array();
    $columns = array(
      0 => 'id',
      1 => 'exp_description',
      2 => 'exp_cate_id',
      3 => 'supp_id',
      4 => 'exp_amount',
      5 => 'exp_remain',
      6 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`exp_description` LIKE '%". $search_value ."%'";
      $SQL .= " OR `e`.`id` LIKE '%".$search_value."%')";
    }
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
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
      $sid = $ROW['supp_id'];
      if ($ROW['exp_cate_id'] == 1) {
        $SUPPLIER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` AS `name` FROM `tbl_staff` WHERE `id` = '$sid'"));
      } else {
        $SUPPLIER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `supp_fname` AS `name` FROM `tbl_supplier` WHERE `id` = '$sid'"));
      }
      $expid = 'EXP-'. sprintf('%05d', $ROW['id']);
      $btnShow = '<a href="expense_payment.php?pgid=17&excode='.$ROW['exp_code'].'" class="btn btn-icon btn-primary mb-2 showBtn"><span class="tf-icons bx bx-receipt"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger mb-2 deleteBtn"><span class="tf-icons bx bx-trash"></a>';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).' <br>'.date('H : i A', strtotime($ROW['timestamp']));
      $amount = $ROW['exp_amount'];
      $remain = $ROW['exp_remain'];
      $sub_array = array();
      $sub_array[] = '<center><a href="../page/expense_payment.php?pgid=17&excode='.$ROW["exp_code"].'">'.$expid.'</a></center>';
      $sub_array[] = '<span class="text-wrap">'.$ROW['exp_description'].'</span>';
      $sub_array[] = '<center>'.$ROW['prod_category'].'</center>';
      $sub_array[] = '<span class="text-center text-wrap">'.$SUPPLIER['name'].'</span>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($amount,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($remain,2).'</span></div>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
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
    $title = array('Total Expense', 'Product', 'Dental Lab', 'Staff');
    $title = ($lang == 1) ? array('Total Expense', 'Product', 'Dental Lab', 'Staff') : array('ចំណាយសរុប', 'ចំណាយសម្ភារៈ', 'ឡាបូទន្តសាស្ត្រ', 'ចំណាយបុគ្គលិក');
    $url = array('expense_list.php?pgid=12', 'expense_list.php?pgid=12', 'expense_list.php?pgid=12', 'expense_list.php?pgid=12');
		$img = array('payment', 'product-success', 'lab-info', 'staff-danger');
    $TOTAL = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(`exp_payment_amount`) AS `expense` FROM `tbl_expense_payment` WHERE (`timestamp` BETWEEN '$start' AND '$end')"));
    $total = number_format($TOTAL['expense'], 0);
    $INVENTORY = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(`exp_payment_amount`) AS `expense` FROM `tbl_expense_payment` AS `ep` INNER JOIN `tbl_invoice_expense` AS `i` ON (`ep`.`exp_id` = `i`.`id`) WHERE (`ep`.`timestamp` BETWEEN '$start' AND '$end') AND `exp_cate_id` = 4"));
    $inventory = number_format($INVENTORY['expense'], 0);
    $LAB = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(`exp_payment_amount`) AS `expense` FROM `tbl_expense_payment` AS `ep` INNER JOIN `tbl_invoice_expense` AS `i` ON (`ep`.`exp_id` = `i`.`id`) WHERE (`ep`.`timestamp` BETWEEN '$start' AND '$end') AND `exp_cate_id` = 2"));
    $lab = number_format($LAB['expense'], 0);
    $STAFF = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(`exp_payment_amount`) AS `expense` FROM `tbl_expense_payment` AS `ep` INNER JOIN `tbl_invoice_expense` AS `i` ON (`ep`.`exp_id` = `i`.`id`) WHERE (`ep`.`timestamp` BETWEEN '$start' AND '$end') AND `exp_cate_id` = 1"));
    $staff = number_format($STAFF['expense'], 0);
		$data = array('<small>$ </small>'.$total, '<small>$ </small>'.$inventory, '<small>$ </small>'.$lab, '<small>$ </small>'.$staff);
    foreach ($title as $i => $t) {
			$o .= '<div class="col-lg-6 col-md-3 col-6 mb-4">';
			$o .=   '<div class="card">';
			$o .=     '<div class="card-body">';
			$o .=       '<div class="card-title d-flex justify-content-between">';
			$o .=         '<div><img src="../assets/img/icons/unicons/'.$img[$i].'.png" alt="Credit Card" width="30px" class="rounded"></div>';
			$o .=         '<div class="dropdown">';
			$o .=            '<button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>';
			$o .=            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6"><a class="dropdown-item" href="'.$url[$i].'">View More</a></div>';
			$o .=         '</div>';
			$o .=       '</div>';
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