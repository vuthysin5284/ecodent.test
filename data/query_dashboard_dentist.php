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
    $uid = $_POST['uid'];
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
    $dates = explode('-', $date);
    $start = str_replace('/', '-', $dates[0]).' 00:00:00';
    $end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT `i`.`id`, DATE_ADD(`i`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `inv_title`, `inv_grandtotal`, `inv_remain`, `i`.`cust_id`, `cust_fname`, `inv_code`, `cust_image`, `i`.`staff_id`, `staff_fname`, `share_amount`, `share_status` FROM `tbl_invoice_patient` AS `i` INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`i`.`staff_id` = `s`.`id`) WHERE (`i`.`timestamp` BETWEEN '$start' AND '$end')";
    $output = array();
    $columns = array(
      0 => 'inv_title',
      1 => 'cust_id',
      2 => 'cust_fname',
      4 => 'inv_grandtotal',
      5 => 'share_amount',
      6 => 'share_status',
      7 => 'staff_id',
      8 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `i`.`cust_id` LIKE '%".$search_value."%')";
    }
    $SQL .= " AND `staff_id` = '$uid' AND `inv_status` > 1 GROUP BY `i`.`id`";
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
    $grandTotal = 0;
    $shareTotal = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $id = $ROW['id'];
      $payment = (float) $ROW['inv_grandtotal'];
      $sharemount = (float) $ROW['share_amount'];
      $grandTotal = $grandTotal + $payment;
      $shareTotal = $shareTotal + $sharemount;
      $cid = 'P-'. sprintf('%05d', $ROW['cust_id']);
      $invid = 'INV-'. sprintf('%05d', $ROW['id']);
      $folder = ($ROW['cust_image'] == '0') ? '' : $cid.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).' <br>'.date('H : i A', strtotime($ROW['timestamp']));
      $btnShare = '<a href="javascript:void();" data-id="'.$ROW['id'].'" data-share="'.$totalshare.'" class="btn btn-icon btn-primary mb-2 shareBtn"><span class="tf-icons bx bx-check"></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger mb-2 deleteBtn"><span class="tf-icons bx bx-x"></a>';
      $status = ($ROW['share_status'] == 0) ? '<span class="badge bg-warning p-2">PENDING</span>' : '<span class="badge bg-success p-2">SHARED</span>';
      $sub_array = array();
      $sub_array[] = '<center><a href="../page/invoice_payment.php?icode='.$ROW["inv_code"].'">'.$ROW['inv_title'].'</a></center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="50" width="50"></center>';
      $sub_array[] = $ROW['cust_fname'];
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($payment,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($sharemount,2).'</span></div>';
      $sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
      'grandTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($grandTotal,2).'</span></div>',
      'shareTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($shareTotal,2).'</span></div>',
    );
    echo json_encode($output);
  }

  function getDataReport() {
    global $CON;
    $o = '';
    $uid = $_POST['uid'];
		$date = $_POST['date'];
		$filter = $_POST['str'];
    $lang = $_POST['lang'];
		if ($filter == 'Custom Range') { $filter = 'Custom Date'; }
		$date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $title = ($lang == 1) ? array('Total Invoice', 'Incomes', 'Appointment', 'Treatment') : array('វិក្កយបត្រសរុប', 'ចំណូល', 'ការណាត់ជួប', 'ការព្យាបាល');
    $url = array('report_invoice.php?pgid=22', 'invoice_completed.php?pgid=11', 'invoice_pending.php?pgid=10', 'invoice_draft.php?pgid=9');
		$img = array('payment', 'paid', 'calendar', 'lab-danger');
    $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_salary` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $TOTAL = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(inv_grandtotal) AS `total` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` > 1 AND `staff_id` = '$uid'"));
    $SHARE = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(share_amount) AS `share` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `staff_id` = '$uid'"));
    $APPO = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_appointment` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `staff_id` = '$uid' AND `appo_status` > 0"));
		$TRMT = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_invoice_treatment` AS `t` INNER JOIN `tbl_invoice_patient` AS `i` ON (`t`.`inv_id` = `i`.`id`) WHERE (`t`.`timestamp` BETWEEN '$start' AND '$end') AND `i`.`staff_id` = '$uid' AND `inv_status` > 1"));
    $total = (float) $TOTAL['total'];
    $share = (float) $SHARE['share'] + (float) $USER['staff_salary'];
    $grandTotal = shortNumber($total);
    $shareTotal = shortNumber($share);
		$data = array('<small>$ </small>'.$grandTotal, '<small>$ </small>'.$shareTotal, $APPO['n'], $TRMT['n']);
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