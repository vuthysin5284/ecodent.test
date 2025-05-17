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
    $SQL = "SELECT `s`.id, DATE_ADD(`s`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `staff_fname`, `staff_image`, `staff_gender`, `staff_dob`, `staff_contact`, `staff_address`, `staff_status`, `user_id` FROM `tbl_staff` AS `s` WHERE (`s`.`timestamp` BETWEEN '$start' AND '$end') AND `staff_status` = 1";
    $output = array();
    $columns = array(
      0 => 'id',
      2 => 'staff_fname',
      3 => 'staff_gender',
      4 => 'staff_dob',
      5 => 'staff_contact',
      6 => 'staff_address'
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`staff_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `s`.`id` LIKE '%".$search_value."%')";
    }
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `s`.`id` DESC"; }
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
				$id = $ROW['id'];
        $user_id = $ROW['user_id'];
        $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$user_id' LIMIT 1"));
				$INCOME = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(payment_amount) AS `income` FROM `tbl_invoice_payment` AS `ip` INNER JOIN `tbl_invoice_patient` AS `i` ON (`ip`.`inv_id` = `i`.`id`) WHERE (`ip`.`timestamp` BETWEEN '$start' AND '$end') AND `staff_id` = '$id'"));
        $incomes = $INCOME['income'];
        $EXPENSE = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(exp_payment_amount) AS `expense`, `exp_status` FROM `tbl_expense_payment` AS `ep` INNER JOIN `tbl_invoice_expense` AS `i` ON (`ep`.`exp_id` = `i`.`id`) WHERE (`ep`.`timestamp` BETWEEN '$start' AND '$end') AND (`exp_cate_id` = 1 AND `supp_id` = '$id')"));
        $expenses = $EXPENSE['expense'];
        $age = (date('Y') - date('Y', strtotime($ROW['staff_dob'])));
        $gender = ($ROW['staff_gender'] == 0) ? 'F' : 'M';
        $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
        $sid = 'S-'. sprintf('%05d', $ROW['id']);      
        $folder = ($ROW['staff_image'] == '0') ? '' : $sid.'/';
				$status = ($EXPENSE['exp_status'] == 3) ? '<span class="badge bg-label-success p-2">PAID</span>' : '<span class="badge bg-label-primary p-2">PENDING</span>';
        $btnShow = '<a href="../page/report_staff_commission.php?sid='.$ROW['id'].'" class="btn btn-icon btn-primary mb-2 showBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><span class="tf-icons bx bx-show"></span></a>';
        $sub_array = array(); 
        $sub_array[] = '<center>'.$sid.'</center>';
        $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['staff_image']. '.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
        $sub_array[] = $ROW['staff_fname'];
        $sub_array[] = '<center>'.$gender.'</center>';
        $sub_array[] = '<center>'.$age.'</center>';
        $sub_array[] = '<center>'.$ROW['staff_contact'].'</center>';
        $sub_array[] = '<center>'.$ROW['staff_address'].'</center>';
				$sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($incomes,2).'</span></div>';
				$sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($expenses,2).'</span></div>';
        $sub_array[] = '<center>'.$btnShow.'</center>';
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
    $title = ($lang == 1) ? array('Incomes', 'Expenses', 'Staff', 'Position') : array('ចំណូល', 'ចំណាយ', 'បុគ្គលិក', 'តួនាទី');
    $url = array('invoice_completed.php?pgid=11', 'expense_list.php?pgid=12', 'staff_list.php?pgid=17', 'staff_position.php?pgid=18');
		$img = array('wallet-success', 'money-danger', 'user', 'bag-info');
    $INCOME = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(payment_amount) AS `income` FROM `tbl_invoice_payment` AS `ip` INNER JOIN `tbl_invoice_patient` AS `i` ON (`ip`.`inv_id` = `i`.`id`) WHERE (`ip`.`timestamp` BETWEEN '$start' AND '$end')"));
    $incomes = number_format($INCOME['income'], 0);
    $EXPENSE = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(exp_payment_amount) AS `expense` FROM `tbl_expense_payment` AS `ep` INNER JOIN `tbl_invoice_expense` AS `i` ON (`ep`.`exp_id` = `i`.`id`) WHERE (`ep`.`timestamp` BETWEEN '$start' AND '$end') AND `exp_cate_id` = 1 "));
    $expenses = number_format($EXPENSE['expense'], 0);
		$STAFF = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_staff` WHERE (`timestamp` BETWEEN '$start' AND '$end')"));
    $CATEG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_staff_position`"));
		$data = array('<small>$ </small>'.$incomes, '<small>$ </small>'.$expenses, $STAFF['n'], $CATEG['n']);
    foreach ($title as $i => $t) {
			$o .= '<div class="col-lg-6 col-md-3 col-6 mb-4">';
			$o .=   '<div class="card">';
			$o .=     '<div class="card-body">';
			$o .=       '<div class="card-title d-flex justify-content-between">';
			$o .=         '<div><img src="../assets/img/icons/unicons/'.$img[$i].'.png" alt="Credit Card" width="30px" class="rounded"></div>';
			$o .=         '<div class="dropdown">';
			$o .=            '<button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>';
			$o .=            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6"><a class="dropdown-item" href="'.$url[$i].'">Show More</a></div>';
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