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
    $SQL = "SELECT 
      SUM(distinct inv_grandtotal) AS `total`, 
      SUM(distinct inv_remain) AS `remain`, 
      SUM(distinct inv_grandtotal)-SUM(distinct inv_remain) AS `paid`,  
      `i`.`id`, 
	    pm.payment_method, 
      DATE_ADD(`i`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, 
      `inv_code`, `cust_id`, `cust_fname`, `cust_image`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `staff_fname`, `inv_title`, `inv_status` 
    FROM `tbl_invoice_patient` AS `i` 
    INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) 
    INNER JOIN `tbl_staff` AS `s` ON (`i`.`staff_id` = `s`.`id`) 
    LEFT JOIN tbl_invoice_payment ip ON ip.inv_id = i.id  
    LEFT JOIN tbl_payment_method pm ON pm.id = ip.paym_id
    WHERE (`i`.`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` > 0";
    $output = array();
    $columns = array(
      0 => 'inv_title',
      2 => 'cust_fname',
      3 => 'total',
      4 => 'remain',
      5 => 'inv_status',
      6 => 'staff_fname',
      7 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `i`.`id` LIKE '%".$search_value."%')";
    }
    if ($_POST['sid'] != '') {
      $sid = $_POST['sid'];
      $SQL .= " AND `i`.`staff_id` = '$sid'";
    }
    if ($_POST['zid'] != '') {
      $zid = $_POST['zid'];
      $SQL .= " AND `inv_status` = '$zid'";
    }
    if ($_POST['pyid'] != '') {
      $pyid = $_POST['pyid'];
      $SQL .= " AND ip.paym_id = '$pyid'";
    }

    $SQL .= " GROUP BY `i`.`id`";
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
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $cid = $ROW['cust_id'];
      $total = ($ROW['total'] == '') ? 0 : $ROW['total'];
      $remain = ($ROW['remain'] == '') ? 0 : $ROW['remain'];
      $paid = ($ROW['paid'] == '') ? 0 : $ROW['paid'];
      $grandTotal = $grandTotal + $total;
      $remainTotal = $remainTotal + $remain;
      $paidTotal = $paidTotal + $paid;
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).' <br>'.date('H : i A', strtotime($ROW['timestamp']));
      $custId = 'P-'. sprintf('%05d', $cid);      
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      if ($ROW['inv_status'] == 2) {
        $status = '<span class="badge bg-warning p-2">PATIAL</span>';
      } else if ($ROW['inv_status'] == 3) {
        $status = '<span class="badge bg-success p-2">PAID</span>';
      } else {
        $status = '<span class="badge bg-secondary p-2">QUOTE</span>';
      }
      $sub_array = array(); 
      $sub_array[] = '<center><a href="../page/invoice_payment.php?pgid=22&icode='.$ROW["inv_code"].'">'.$ROW['inv_title'].'</a></center>';
      // $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image']. '.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
      $sub_array[] = $ROW['cust_fname'];
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($total,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($remain,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($paid,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>'.$ROW['payment_method'].'</span></div>';
      $sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
      'grandTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($grandTotal,2).'</span></div>',
      'remainTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($remainTotal,2).'</span></div>',
      'paidTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($paidTotal,2).'</span></div>',
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
    $title = ($lang == 1) ? array('Total Invoice', 'Has Paid', 'In Pending', 'Drafts') : array('វិក្កយបត្រសរុប', 'បានទូទាត់រួច', 'មិនទាន់ទូទាត់', 'វិក្កយបត្រព្រៀង');
    $url = array('report_invoice.php?pgid=22', 'invoice_completed.php?pgid=11', 'invoice_pending.php?pgid=10', 'invoice_draft.php?pgid=9');
		$img = array('payment', 'paid', 'pending', 'draft');
    $TOTAL = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(inv_grandtotal) AS `total` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND (`inv_status` > 0)"));
    $PENDING = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(inv_remain) AS `pending` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND (`inv_status` = 2)"));
    $INCOME = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(payment_amount) AS `income` FROM `tbl_invoice_payment` WHERE (`timestamp` BETWEEN '$start' AND '$end')"));
    $DRAFT = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(inv_grandtotal) AS `draft` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND (`inv_status` = 1)"));
    $total = shortNumber($TOTAL['total']);
    $pending = shortNumber($PENDING['pending']);
    $incomes = shortNumber($INCOME['income']);
    $draft = shortNumber($DRAFT['draft']);
		$data = array('<small>$ </small>'.$total, '<small>$ </small>'.$incomes, '<small>$ </small>'.$pending, '<small>$ </small>'.$draft);
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