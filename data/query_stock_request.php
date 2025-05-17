<?php
  session_start();
	include_once ('../inc/config.php');
	include_once ('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow ('tbl_stock_request'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else if ($qid == 6) { fnApprove(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_stock_request` SET `request_status` = 0 WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

  function fnApprove() {
    global $CON;
    $id = $_POST['id'];
    $PRODUCT = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `prod_id`, `request_qty`, `prod_qty` FROM `tbl_stock_request` AS `s` INNER JOIN `tbl_product` AS `p` ON (`s`.`prod_id` = `p`.`id`) WHERE `s`.`id` = '$id' LIMIT 1"));
    $pid = $PRODUCT['prod_id'];
    $prod_qty = (float) $PRODUCT['prod_qty'];
    $req_qty = (float) $PRODUCT['request_qty'];
    $new_qty = $prod_qty - $req_qty;
    $UPDATE = mysqli_query($CON, "UPDATE `tbl_product` SET `prod_qty` = '$new_qty' WHERE `id` = '$pid'");
    $SQL = "UPDATE `tbl_stock_request` SET `request_status` = 2 WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $uid = $_POST['uid'];
    $date = $_POST['date'];
    $date = str_replace(' ', '', $date);
		$dates = explode('-', $date);
		$start = str_replace('/', '-', $dates[0]).' 00:00:00';
		$end = str_replace('/', '-', $dates[1]).' 23:59:59';
    $LOG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $SQL = "SELECT `sr`.*, `prod_description`, `prod_category`, `supp_fname`, `prod_image`, `staff_fname`, `request_status` FROM `tbl_stock_request` AS `sr` INNER JOIN `tbl_product` AS `p` ON (`sr`.`prod_id` = `p`.`id`) INNER JOIN `tbl_product_category` AS `pc` ON (`p`.`prod_cate_id` = `pc`.`id`) INNER JOIN `tbl_supplier` AS `sup` ON (`p`.`supp_id` = `sup`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`sr`.`user_id` = `s`.`id`) WHERE (`sr`.`timestamp` BETWEEN '$start' AND '$end') AND `request_status` > 0";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      1 => 'prod_id',
      2 => 'prod_description',
      3 => 'prod_qty',
      4 => 'timestamp',
      5 => 'request_status',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND `prod_description` LIKE '%". $search_value ."%'";
    }
    if (isset($_POST['order'])) {
    	$column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `sr`.`timestamp` DESC"; }
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
      $user_id = $ROW['user_id'];
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$user_id' LIMIT 1"));
      $prodid = 'PRO-'. sprintf('%05d', $ROW['prod_id']);
      $folder = ($ROW['prod_image'] == '0') ? '' : $prodid.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $status = ($ROW['request_status'] == 1) ? '<span class="badge bg-label-primary p-2">PENDING</span>' : '<span class="badge bg-label-success p-2">APPROVED</span>';
      $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary mb-2 showBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Approve"><span class="tf-icons bx bx-check"></span></a>';
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning mb-2 editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger mb-2 deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      } 
      
      $sub_array = array();
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['prod_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="100" width="100">'.'<h5 class="mt-2"><span class="badge bg-label-primary" style="width:100px">'.$prodid.'</span></h5></center>';
      $sub_array[] = '<h4 class="body-text mb-2">'.$ROW['prod_description'].'</h4><i class="bx bx-package"></i> '.$ROW['prod_category'].'<br><i class="bx bx-store"></i> '.$ROW['supp_fname'];
      $sub_array[] = '<center>'.$ROW['request_qty'].'</center>';
      $sub_array[] = '<center>'.$USER['staff_fname'].'<br>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] = ($ROW['request_status'] == 1 && $LOG['staff_position_id'] == 1) ? '<center>'.$btnShow.'<br>'.$btnEdit.'<br>'.$btnDelete.'<center>' : '<center></center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
    );
    echo json_encode($output);
  }

  function getLastId() {
    global $CON;
    $SQL = "SELECT id FROM tbl_membership ORDER BY id DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }
  function fnInsert() {
    global $CON;
    $uid = $_POST['uid'];
    $product = $_POST['product'];
    $qty = $_POST['qty'];
    $SQL = "INSERT INTO `tbl_stock_request` (`prod_id`, `request_qty`, `user_id`, `request_status`) VALUES ('$product', '$qty', '$uid', 1)";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $uid = $_POST['uid'];
    $product = $_POST['product'];
    $qty = $_POST['qty'];
    $SQL = "UPDATE `tbl_stock_request` SET `prod_id` = '$product', `request_qty` = '$qty', `user_id` = '$uid' WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

?>