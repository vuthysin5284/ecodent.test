<?php
  session_start();
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
    $SQL = "SELECT `r`.`prod_id`, DATE_ADD(`r`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `prod_description`, `prod_unit_cost`, `prod_image`, `prod_category`, `supp_fname`, `staff_fname`, SUM(`request_qty`) AS `qty`, SUM(`request_qty`) * `prod_unit_cost` AS `total` FROM `tbl_stock_request` AS `r` INNER JOIN `tbl_product` AS `p` ON (`r`.`prod_id` = `p`.`id`) INNER JOIN `tbl_product_category` AS `pc` ON (`p`.`prod_cate_id` = `pc`.`id`) INNER JOIN `tbl_supplier` AS `sup` ON (`p`.`supp_id` = `sup`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`r`.`user_id` = `s`.`id`) WHERE (`r`.`timestamp` BETWEEN '$start' AND '$end') AND (`prod_status` = 1 AND `request_status` = 2)";
    $output = array();
    $columns = array(
      1 => 'prod_id',
      2 => 'prod_description',
      3 => 'qty',
      4 => 'prod_unit_cost',
      5 => 'total',
      6 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`prod_description` LIKE '%". $search_value ."%'";
      $SQL .= " OR `r`.`prod_id` LIKE '%".$search_value."%')";
    }
    $SQL .= " GROUP BY `r`.`prod_id`, `r`.`user_id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `r`.`timestamp` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning mb-2 editBtn"><span class="tf-icons bx bx-edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger mb-2 deleteBtn"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      }
      
      
      $prodid = 'PRO-'. sprintf('%05d', $ROW['prod_id']);
      $folder = ($ROW['prod_image'] == '0') ? '' : $prodid.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $qty = $ROW['qty'];
      $cost = $ROW['prod_unit_cost'];
      $total = $qty * $cost;
      $sub_array = array();
      $sub_array[] = '<center>'.$prodid.'<center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['prod_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="50" width="50"></center>';
      $sub_array[] = $ROW['prod_description'];
      $sub_array[] = '<center>'.$qty.'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($cost,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($total,2).'</span></div>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'<br>'.$timestamp.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
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
    $title = ($lang == 1) ? array('Expense', 'Product', 'Supplier', 'Stock Alert') : array('ការចំណាយ', 'សម្ភារៈ', 'អ្នកផ្គត់ផ្គង់', 'ជិតអស់ស្តុក');
    $url = array('patient_list.php?pgid=7', 'notification_appointment.php?pgid=2', 'notification_served_history.php?pgid=6', 'deleted_patient.php?pgid=27');
		$img = array('payment', 'product-success', 'store-info', 'alert-danger');
		$PRODUCT = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_product` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `prod_status` = 1"));
		$SUPPLIER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_supplier` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND (`exp_cate_id` = 4 AND `supp_status` = 1)"));
		$ALERT = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_product` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND (`prod_qty` < `prod_min_qty` AND `prod_status` = 1)"));
    $INVENTORY = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(`request_qty` * `prod_unit_cost`) AS `expense` FROM `tbl_stock_request` AS `r` INNER JOIN `tbl_product` AS `p` ON (`r`.`prod_id` = `p`.`id`) WHERE (`r`.`timestamp` BETWEEN '$start' AND '$end') AND `request_status` = 2"));
    $inventory = number_format($INVENTORY['expense'], 0);
		$data = array('<small>$ </small>'.$inventory, $PRODUCT['n'], $SUPPLIER['n'], $ALERT['n']);
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