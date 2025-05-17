<?php
  include_once('../inc/config.php');
  include_once('../inc/class.upload.php');
  include_once('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnRecovery(); }
  else { }

  function fnDelete() {
    global $CON;
    $cust_id = $_POST['id'];
    $TP = mysqli_query($CON, "SELECT `id` FROM `tbl_treatment_plan` WHERE `cust_id` = '$cust_id'");
    while ($TPROW = mysqli_fetch_assoc($TP)) {
      $tpid = $TPROW['id'];
      mysqli_query($CON, "DELETE FROM `tbl_treatment` WHERE `tmpl_id` = '$tpid'");
    }

    $INV = mysqli_query($CON, "SELECT `id` FROM `tbl_invoice_patient` WHERE `cust_id` = '$cust_id'");
    while ($INVROW = mysqli_fetch_assoc($INV)) {
      $invid = $INVROW['id'];
      mysqli_query($CON, "DELETE FROM `tbl_invoice_payment` WHERE `inv_id` = '$invid'");
      mysqli_query($CON, "DELETE FROM `tbl_invoice_treatment` WHERE `inv_id` = '$invid'");
    }
    mysqli_query($CON, "DELETE FROM `tbl_treatment_plan` WHERE `cust_id` = '$cust_id'");
    mysqli_query($CON, "DELETE FROM `tbl_invoice_patient` WHERE `cust_id` = '$cust_id'");
    $SQL = "DELETE FROM `tbl_customer` WHERE `id` = '$cust_id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnRecovery() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_customer` SET `cust_status` = 1 WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $SQL = "SELECT `c`.*, `staff_fname` FROM `tbl_customer` AS `c` INNER JOIN `tbl_staff` AS `s` ON (`c`.`user_id` = `s`.`id`) WHERE `cust_status` = 0";
    $output = array();
    $columns = array(
      0 => 'id',
      2 => 'cust_fname',
      3 => 'cust_gender',
      4 => 'cust_dob',
      5 => 'cust_contact',
      6 => 'cust_address',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `c`.`id` LIKE '%".$search_value."%')";
    }
    $SQL .= " GROUP BY `c`.`id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    }
    else { $SQL .= " ORDER BY `c`.`id` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $gender = ($ROW['cust_gender'] == 0) ? 'F' : 'M';
      $cid = 'P-'. sprintf('%05d', $ROW['id']);
      $folder = ($ROW['cust_image'] == '0') ? '' : $cid.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).' <br>'.date('H : i A', strtotime($ROW['timestamp']));
      $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary mb-2 recoveryBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore"><span class="tf-icons bx bx-history"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger mb-2 deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      $sub_array = array();
      $sub_array[] = '<center>'.$cid.'</center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image']. '.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
      $sub_array[] = $ROW['cust_fname'];
      $sub_array[] = '<center>'.$gender.'</center>';
      $sub_array[] = '<center>'.$age.'</center>';
      $sub_array[] = '<center>'.$ROW['cust_contact'].'</center>';
      $sub_array[] = '<center>'.$ROW['cust_address'].'</center>';
      $sub_array[] = '<center>'.$btnShow.' '.$btnDelete.'<center>';
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