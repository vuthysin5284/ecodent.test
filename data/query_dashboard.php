<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  
  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getDataReport(); }
  else if ($qid == 3) { getTreatmentStatistics(); }
  else if ($qid == 4) { getDataTransaction(); }
  else if ($qid == 5) { getDataStockAlert(); }
  else { }

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
    $title = ($lang == 1) ? array('Incomes', 'Expenses', 'Patients', 'Staffs') : array('ចំណូល', 'ចំណាយ', 'អតិថិជន', 'បុគ្គលិក');
		$img = array('wallet-success', 'money-danger', 'walk-info', 'user');
    $url = array('report_invoice.php?pgid=22', 'report_expense.php?pgid=24', 'report_patient.php?pgid=21', 'report_staff.php?pgid=26');
    $INC = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(case when paym_id IN(1) then `payment_amount` ELSE 0 END ) AS `incomes`,SUM(case when paym_id >1 then `payment_amount` ELSE 0 END ) AS `bank` FROM `tbl_invoice_payment` WHERE (`timestamp` BETWEEN '$start' AND '$end')"));
    $EXP = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(`exp_payment_amount`) AS `expenses` FROM `tbl_expense_payment` WHERE (`timestamp` BETWEEN '$start' AND '$end')"));
    $CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_customer` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` = 1"));
		$USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(`id`) AS `n` FROM `tbl_staff` WHERE `staff_status` = 1"));
    $incomes = "<h5>BANK ".shortNumber($INC['bank'])."$</h5><h5>CASH ".shortNumber($INC['incomes'])."$</h5>";
    $expenses = shortNumber($EXP['expenses']);
		$customers = number_format($CUST['n'], 0);
    $staffs = number_format($USER['n'], 0);
		$data = array($incomes, '<small>$ </small>'.$expenses, $customers, $staffs);
    $text_dropdown = ($lang == 1) ? 'Show More' : 'ពត៌មានបន្ថែម';
    foreach ($title as $i => $t) {
			$o .= '<div class="col-lg-6 col-md-3 col-6 mb-4">';
			$o .=   '<div class="card">';
			$o .=     '<div class="card-body">';
			$o .=       '<div class="card-title d-flex justify-content-between">';
			$o .=         '<div><img src="../assets/img/icons/unicons/'.$img[$i].'.png" alt="Credit Card" width="30px" class="rounded"></div>';
			$o .=         '<div class="dropdown">';
			$o .=            '<button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>';
			$o .=            '<div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6"><a class="dropdown-item" href="'.$url[$i].'">'.$text_dropdown.'</a></div>';
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

  function getTreatmentStatistics() {
    global $CON;
    $o = '';
    $i = 0;
		$date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $color = array('success', 'primary', 'info', 'warning');
    $SQL = "SELECT `service_cate_id`, `prod_category`, SUM(`service_price`) AS `price`, SUM(`tooth_qty`) AS `qty` FROM `tbl_invoice_treatment` AS `t` INNER JOIN `tbl_treatment_service` AS `s` ON (`t`.`tmsv_id` = `s`.`id`) INNER JOIN `tbl_invoice_patient` AS `i` ON (`t`.`inv_id` = `i`.`id`) INNER JOIN `tbl_product_category` AS `c` ON (`s`.`service_cate_id` = `c`.`id`) INNER JOIN `tbl_customer` AS `p` ON (`i`.`cust_id` = `p`.`id`) WHERE (`t`.`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` = 1 AND `inv_status` > 1 GROUP BY `service_cate_id` ORDER BY `qty` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $qty = (int) $ROW['qty'];
      $price = (float) $ROW['service_price'];
      $total = $qty * $price;
      if ($ROW['service_cate_id'] == 1) { $icon = 'O'; }
      else if ($ROW['service_cate_id'] == 2) { $icon = 'I'; }
      else if ($ROW['service_cate_id'] == 3) { $icon = 'R'; }
      else if ($ROW['service_cate_id'] == 4) { $icon = 'S'; }
      else if ($ROW['service_cate_id'] == 5) { $icon = 'G'; }
      else { $icon = 'L'; }
      
      if ($i < 4) {
        $o.= '<li class="d-flex mb-4 pb-1">';
        $o.= '<div class="avatar flex-shrink-0 me-3">';
        $o.= '<span class="avatar-initial rounded bg-label-'.$color[$i].'">'.$icon.'</span>';
        $o.= '</div>';
        $o.= '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">';
        $o.= '<div class="me-2">';
        $o.= '<h6 class="mb-0">'.$ROW['prod_category'].'</h6>';
        $o.= '<small class="text-muted">$ '.number_format($ROW['price'], 0).'</small>';
        $o.= '</div>';
        $o.= '<div class="user-progress">';
        $o.= '<small class="fw-semibold">'.$ROW['qty'].'</small>';
        $o.= '</div>';
        $o.= '</div>';
        $o.= '</li>';  
      }
      $i = $i + 1;
    }
    echo $o;
  }

  function getDataTransaction() {
    global $CON;
    $o = '';
		$date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $SQL = "SELECT * FROM `tbl_transaction` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `trans_status` > 0 ORDER BY `timestamp` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $id = $ROW['payment_id'];
      if ($ROW['trans_status'] == 1) {
        $TRANS = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `cust_id`, `paym_id`, `method_image`, `payment_method`, `payment_amount` AS `trans_amount` FROM `tbl_invoice_payment` AS `i` INNER JOIN `tbl_invoice_patient` AS `p` ON (`i`.`inv_id` = `p`.`id`) INNER JOIN `tbl_payment_method` AS `m` ON (`i`.`paym_id` = `m`.`id`) WHERE `i`.`id` = '$id' LIMIT 1"));
        $cid = $TRANS['cust_id'];
        $DES = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `cust_fname` AS `name` FROM `tbl_customer` WHERE `id` = '$cid' LIMIT 1"));
      } else {
        $TRANS = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `exp_cate_id`, `supp_id`, `paym_id`, `method_image`, `payment_method`, `exp_payment_amount` AS `trans_amount` FROM `tbl_expense_payment` AS `e` INNER JOIN `tbl_invoice_expense` AS `i` ON (`e`.`exp_id` = `i`.`id`) INNER JOIN `tbl_payment_method` AS `m` ON (`e`.`paym_id` = `m`.`id`) WHERE `e`.`id` = '$id' LIMIT 1"));
        $sid = $TRANS['supp_id'];
        if ($TRANS['exp_cate_id'] == 1) {
          $DES = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` AS `name` FROM `tbl_staff` WHERE `id` = '$sid' LIMIT 1"));
        } else {
          $DES = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `supp_fname` AS `name` FROM `tbl_supplier` WHERE `id` = '$sid' LIMIT 1"));
        }
      }
      $name = $DES['name'];
      $image = $TRANS['method_image'];
      $folder = 'M-'. sprintf('%05d', $TRANS['paym_id']);
      $image_path = ($TRANS['method_image'] == '0') ? '../images/profiles/0.jpg' : '../images/profiles/'.$folder.'/'.$image.'.jpg';
      $status = ($ROW['trans_status'] == 1) ? '<h6 class="mb-0 text-primary">+ '.number_format($TRANS['trans_amount'], 2).'</h6>' : '<h6 class="mb-0 text-danger">- '.number_format($TRANS['trans_amount'], 2).'</h6>';
      if($i < 6) {
        $o.= '<li class="d-flex mb-4 pb-1">';
        $o.= '<div class="avatar flex-shrink-0 me-3">';
        $o.= '<img src="'.$image_path.'" alt="User" class="rounded" />';
        $o.= '</div>';
        $o.= '<div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">';
        $o.= '<div class="me-2">';
        $o.= '<small class="text-muted d-block mb-1">'.$TRANS['payment_method'].'</small>';
        $o.= '<h6 class="mb-0">'.$name.'</h6>';
        $o.= '</div>';
        $o.= '<div class="user-progress d-flex align-items-center gap-1">'.$status.'<span class="text-muted">USD</span></div>';
        $o.= '</div>';
        $o.= '</li>';  
      }
      $i = $i + 1;
    }
    echo $o;
  }

  function getDataStockAlert() {
    global $CON;
    $o = '';
		$date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $ALERT = mysqli_query($CON, "SELECT `p`.`id`, `prod_description`, `prod_image`, `prod_qty`, `prod_category` FROM `tbl_product` AS `p` INNER JOIN `tbl_product_category` AS `c` ON (`p`.`prod_cate_id` = `c`.`id`) WHERE (`prod_qty` < `prod_min_qty` AND `prod_status` = 1)");
    $i=0;
    while ($ROW = mysqli_fetch_assoc($ALERT)) {
      $folder = 'PRO-'. sprintf('%05d', $ROW['id']);
      if($i < 6) {
        $o.= '<li class="d-flex mb-4 pb-1">
        <div class="avatar flex-shrink-0 me-3">
          <img src="../images/profiles/'.$folder.'/'.$ROW['prod_image'].'.jpg" alt="User" class="rounded" />
        </div>
        <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
          <div class="me-2">
            <small class="text-muted d-block mb-1">'.$ROW['prod_category'].'</small>
            <h6 class="mb-0">'.$ROW['prod_description'].'</h6>
          </div>
          <div class="user-progress d-flex align-items-center gap-1">
            <h6 class="mb-0">'.$ROW['prod_qty'].'</h6>
            <span class="text-muted"></span>
          </div>
        </div>
      </li>';  
      }
      $i = $i + 1;
    }
    echo $o;
  }
  
?>