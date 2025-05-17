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
    $SQL = "SELECT `c`.id, DATE_ADD(`c`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `cust_fname`, `cust_image`, `cust_code`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `staff_fname`, `cust_status` FROM `tbl_customer` AS `c` INNER JOIN `tbl_staff` AS `s` ON (`c`.`user_id` = `s`.`id`) WHERE (`c`.`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` = 1";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      0 => 'id',
      2 => 'cust_fname',
      3 => 'cust_gender',
      4 => 'cust_dob',
      5 => 'cust_contact',
      6 => 'cust_address',
      9 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `c`.`id` LIKE '%".$search_value."%')";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `c`.`id` DESC"; }
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
				$TREATMENT = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_invoice_treatment` AS `t` INNER JOIN `tbl_invoice_patient` AS `i` ON (`t`.`inv_id` = `i`.`id`) WHERE (`t`.`timestamp` BETWEEN '$start' AND '$end') AND `cust_id` = '$id'"));
				$INVOICE = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(inv_grandtotal) AS `total`, SUM(inv_remain) AS `remain`, `inv_status` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND (`inv_status` BETWEEN 2 AND 3) AND `cust_id` = '$id'"));
        $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
        $gender = ($ROW['cust_gender'] == 0) ? 'F' : 'M';
        $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).' <br>'.date('H : i A', strtotime($ROW['timestamp']));
        $cid = 'P-'. sprintf('%05d', $ROW['id']);      
        $folder = ($ROW['cust_image'] == '0') ? '' : $cid.'/';
				$total = ($INVOICE['total'] == '') ? 0 : $INVOICE['total'];
        $remain = ($INVOICE['remain'] == '') ? 0 : $INVOICE['remain'];
        $sub_array = array(); 
        $sub_array[] = '<center><a href="../page/patient_treatment_plan.php?pgid=7&cid='.$ROW["cust_code"].'">'.$cid.'</a></center>';
        $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image'].'.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
        $sub_array[] = $ROW['cust_fname'];
        $sub_array[] = '<center>'.$gender.'</center>';
				$sub_array[] = '<center>'.$age.'</center>';
        $sub_array[] = '<center>'.$ROW['cust_contact'].'</center>';
        $sub_array[] = '<center>'.$ROW['cust_address'].'</center>';
        $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($total,2).'</span></div>';
				$sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($remain,2).'</span></div>';
				$sub_array[] = '<center>'.$timestamp.'</center>';
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
    $title = ($lang == 1) ? array('Patients', 'Appointments', 'Treatments', 'Deletes') : array('អតិថិជន', 'ការណាត់ជួប', 'ទទួលសេវាកម្ម', 'បានលុប');
    $url = array('patient_list.php?pgid=7', 'notification_appointment.php?pgid=2', 'notification_served_history.php?pgid=6', 'deleted_patient.php?pgid=27');
		$img = array('user', 'calendar', 'done', 'trash');
		$CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_customer` WHERE (`timestamp` BETWEEN '$start' AND '$end')"));
		$APPO = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_appointment` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `appo_status` > 0"));
		$TRMT = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_invoice_treatment` AS `t` INNER JOIN `tbl_invoice_patient` AS `i` ON (`t`.`inv_id` = `i`.`id`) WHERE (`t`.`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` > 1"));
		$DEL = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_customer` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` = 0"));
    $customer = number_format($CUST['n'], 0);
    $appointment = number_format($APPO['n'], 0);
    $treatment = number_format($TRMT['n'], 0);
    $delete = number_format($DEL['n'], 0);
		$data = array($customer, $appointment, $treatment, $delete);
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