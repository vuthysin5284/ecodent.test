<?php
  session_start();
  include_once ('../inc/config.php');   
  $qid = $_POST['qid'];
  if ($qid == 1) { fnTable(); }
  else { }

	function fnTable() {
    global $CON;
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT `i`.`id`, DATE_ADD(`ip`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `amount_en`, `amount_kh`, `payment_amount`, `change_amount`, `payment_method`, `inv_code`, `cust_id`, `cust_fname`, `cust_image`, `staff_fname`, `inv_title`, `inv_status` FROM `tbl_invoice_payment` AS `ip` INNER JOIN `tbl_invoice_patient` AS `i` ON (`ip`.`inv_id` = `i`.`id`) INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`i`.`staff_id` = `s`.`id`) INNER JOIN `tbl_payment_method` AS `pm` ON (`ip`.`paym_id` = `pm`.`id`) WHERE (`ip`.`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` > 0";
    $output = array();
    $columns = array(
      0 => 'id',
      2 => 'cust_fname',
      3 => 'inv_title',
      4 => 'payment_method',
      5 => 'amount_en',
      6 => 'amount_kh',
      7 => 'change_amount',
      8 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `i`.`id` LIKE '%".$search_value."%')";
    }
    $SQL .= " GROUP BY `ip`.`id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `ip`.`timestamp` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    }
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    $usdTotal = 0;
    $khrTotal = 0;
    $changeTotal = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $cid = $ROW['cust_id'];
      $custId = 'P-'. sprintf('%05d', $cid);
      $invid = 'INV-'. sprintf('%05d', $ROW['id']);
      $payment = (float) $ROW['payment_amount'];
      $change = (float) $ROW['change_amount'] * $_SESSION['rating'];
      $amount_en = (float) $ROW['amount_en'];
      $amount_kh = (float) $ROW['amount_kh'];
      $usdTotal = $usdTotal + $amount_en;
      $khrTotal = $khrTotal + $amount_kh;
      $changeTotal = $changeTotal + $change;
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).' <br>'.date('H : i A', strtotime($ROW['timestamp']));
      $sub_array = array(); 
      $sub_array[] = '<center><a href="../page/invoice_payment.php?icode='.$ROW["inv_code"].'">'.$invid.'</a></center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image']. '.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
      $sub_array[] = $ROW['cust_fname'];
      $sub_array[] = $ROW['inv_title'];
      $sub_array[] = '<center>'.$ROW['payment_method'].'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($amount_en,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>៛</span><span>'.number_format($amount_kh,0).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>៛</span><span>'.number_format($change,0).'</span></div>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
      'usdTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($usdTotal,2).'</span></div>',
      'khrTotal' => '<div class="d-flex justify-content-between p-0"><span>៛</span><span>'.number_format($khrTotal,0).'</span></div>',
      'changeTotal' => '<div class="d-flex justify-content-between p-0"><span>៛</span><span>'.number_format($changeTotal,0).'</span></div>',
    );
    echo json_encode($output);
  }