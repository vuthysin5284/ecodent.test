<?php
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
    $SQL = "SELECT `i`.`id`, `i`.`inv_code`, DATE_ADD(`t`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `service_description`, `tooth_qty`, `tooth_id` FROM `tbl_invoice_treatment` AS `t` INNER JOIN `tbl_treatment_service` AS `s` ON (`t`.`tmsv_id` = `s`.`id`) INNER JOIN `tbl_invoice_patient` AS `i` ON (`t`.`inv_id` = `i`.`id`) INNER JOIN `tbl_product_category` AS `c` ON (`s`.`service_cate_id` = `c`.`id`) INNER JOIN `tbl_customer` AS `p` ON (`i`.`cust_id` = `p`.`id`) WHERE (`t`.`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` = 1 AND `inv_status` > 1";
    $output = array();
    $columns = array(
      0 => 'inv_id',
      1 => 'service_description',
      2 => 'tooth_id',
      3 => 'tooth_qty',
      4 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`service_description` LIKE '%". $search_value ."%'";
      $SQL .= " OR `i`.`id` LIKE '%".$search_value."%')";
    }
    if ($_POST['sid'] != '') {
      $sid = $_POST['sid'];
      $SQL .= " AND `service_cate_id` = '$sid'";
    }
    $SQL .= " GROUP BY `t`.`id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `t`.`timestamp` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    }
		$QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    $total = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $qty = $ROW['tooth_qty'];
      $total = $total + $qty;
      $invid = 'INV-'. sprintf('%05d', $ROW['id']);
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).' <br>'.date('H : i A', strtotime($ROW['timestamp']));
      $sub_array = array(); 
      $sub_array[] = '<center><a href="../page/invoice_payment.php?icode='.$ROW["inv_code"].'">'.$invid.'</a></center>';
      $sub_array[] = $ROW['service_description'];
      $sub_array[] = '<center>'.$ROW['tooth_id'].'</center>';
      $sub_array[] = '<center>'.$ROW['tooth_qty'].'</center>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
      'grandTotal' => '<div class="text-center fw-bolder">'.$total.'</div>',
    );
    echo json_encode($output);
  }
?>