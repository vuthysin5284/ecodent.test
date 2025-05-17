<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  
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
    $SQL = "SELECT `ip`.`id`, DATE_ADD(`ip`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `ip`.`user_id`, `inv_title`, `inv_code`, `cust_code`, `cust_id`, 
    `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_address`, `staff_fname`,inv_remain
    FROM `tbl_invoice_patient` AS `ip` 
    INNER JOIN `tbl_customer` AS `c` ON (`ip`.`cust_id` = `c`.`id`) 
    INNER JOIN `tbl_staff` AS `s` ON (`ip`.`staff_id` = `s`.`id`) 
    WHERE `inv_status` in(1,2)";
    //WHERE (`ip`.`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` = 1
    $QUERY1 = mysqli_query($CON, $SQL);   
    $total_all_rows = mysqli_num_rows($QUERY1);
    $output = array();
    $columns = array(
      1 => 'cust_id',
      2 => 'cust_fname',
      3 => 'inv_title',
      4 => 'staff_id',
      5 => 'inv_status',
      6 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `cust_id` LIKE '%".$search_value."%')";
    }
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
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $uid = $ROW['user_id'];
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $btnShow = '<a href="print_invoice_draft.php?pgid='.$_POST['pgid'].'&icode='.$ROW['inv_code'].'" class="btn btn-icon btn-primary showBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Print"><span class="tf-icons bx bx-printer"></span></a>';
        
      $remain = ($ROW['inv_remain'] == '') ? 0 : $ROW['inv_remain'];
      $remainTotal = $remainTotal + $remain; 

      $status = '<span class="badge bg-secondary p-2">Pending</span>'; 
      $sub_array = array(); 
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center>'.$ROW['inv_title'].'</center>';
      $sub_array[] =  $ROW['cust_fname'] ;
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($remain,2).'</span></div>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$btnShow .'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data, 
      'remainTotal' => '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($remainTotal,2).'</span></div>', 
    );
    echo  json_encode($output);
  }


?>