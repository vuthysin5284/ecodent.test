<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];

  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnRecovery(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    mysqli_query($CON, "DELETE FROM `tbl_invoice_treatment` WHERE `inv_id` = '$id'");
    mysqli_query($CON, "DELETE FROM `tbl_invoice_payment` WHERE `inv_id` = '$id'");
    $QUERY = mysqli_query($CON, "DELETE FROM `tbl_invoice_patient` WHERE `id` = '$id' LIMIT 1");
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnRecovery() {
    global $CON;
    $id = $_POST['id'];
    $INV = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `inv_grandtotal`, `inv_remain` FROM `tbl_invoice_patient` WHERE `id` = '$id' LIMIT 1"));
    $grandtotal = $INV['inv_grandtotal'];
    $remain = $INV['inv_remain'];
    $total = $grandtotal - $remain;
    if ($total == 0) {
      $status = 1;
    } else if ($total >= $grandtotal) {
      $status = 3;
    } else {
      $status = 2;
    }
    $SQL = "UPDATE `tbl_invoice_patient` SET `inv_status` = '$status' WHERE `id` = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }
  
  function fnTable() {
    global $CON;
    $SQL = "SELECT `i`.`id`, DATE_ADD(`i`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `inv_title`, `cust_id`, `cust_image`, `cust_fname`, `staff_fname` FROM `tbl_invoice_patient` AS `i` INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`i`.`staff_id` = `s`.`id`) WHERE `inv_status` = 0";
    $output = array();
    $columns = array(
      0 => 'id',
      2 => 'cust_fname',
      3 => 'inv_title',
      4 => 'inv_status',
      5 => 'staff_fname',
      6 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `cust_id` LIKE '%".$search_value."%')";
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
      $invid = 'INV-'. sprintf('%05d', $ROW['id']);
      $cid = 'P-'. sprintf('%05d', $ROW['cust_id']);      
      $folder = ($ROW['cust_image'] == '0') ? '' : $cid.'/';
      if ($ROW['inv_status'] == 2) {
        $status = '<span class="badge bg-warning p-2">PENDING</span>';
      } else if ($ROW['inv_status'] == 3) {
        $status = '<span class="badge bg-success p-2">PAID</span>';
      } else {
        $status = '<span class="badge bg-secondary p-2">QUOTE</span>';
      }
      $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary recoveryBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore"><span class="tf-icons bx bx-history"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger deleteBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      $sub_array = array(); 
      $sub_array[] = '<center>'.$invid.'</center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image']. '.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
      $sub_array[] = $ROW['cust_fname'];
      $sub_array[] = $ROW['inv_title'];
      $sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$btnShow.' '.$btnDelete.'</center>';
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
?>