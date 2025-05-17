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
    $SQL = "DELETE FROM `tbl_invoice_expense` WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
	}

  function fnRecovery() {
    global $CON;
		$id = $_POST['id'];
    $EXP = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `exp_amount`, `exp_remain` FROM `tbl_invoice_expense` WHERE `id` = '$id' LIMIT 1"));
    $amount = $EXP['exp_amount'];
    $remain = $EXP['exp_remain'];
    $total = $amount - $remain;
    $status = ($total < $amount) ? 1 : 2;
    $SQL = "UPDATE `tbl_invoice_expense` SET `exp_status` = $status WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
	}

	function fnTable() {
    global $CON;
		$SQL = "SELECT `e`.*, `prod_category`, `supp_fname`, `staff_fname` FROM `tbl_invoice_expense` AS `e` INNER JOIN `tbl_product_category` AS `c` ON (`e`.`exp_cate_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `tf` ON (`e`.`supp_id` = `tf`.`id`) INNER JOIN `tbl_supplier` AS `s` ON (`e`.`supp_id` = `s`.`id`) WHERE `exp_status` = 0";
    $output = array();
    $columns = array(
      0 => 'id',
      1 => 'exp_description',
      2 => 'prod_category',
      3 => 'supp_id',
      4 => 'exp_status',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`exp_description` LIKE '%". $search_value ."%'";
      $SQL .= " OR `e`.`id` LIKE '%".$search_value."%')";
    }
    $SQL .= " GROUP BY `e`.`id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `e`.`timestamp` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $expid = 'EXP-'. sprintf('%05d', $ROW['id']);
      $supplier = ($ROW['exp_cate_id'] == 1) ? $ROW['staff_fname'] : $ROW['supp_fname'];
      $status = ($total < $amount) ? '<span class="badge bg-warning p-2">PENDING</span>' : '<span class="badge bg-success p-2">PAID</span>';
      $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary mb-2 recoveryBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore"><span class="tf-icons bx bx-history"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger mb-2 deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      $sub_array = array();
      $sub_array[] = '<center>'.$expid.'</center>';
      $sub_array[] = $ROW['exp_description'];
			$sub_array[] = '<center>'.$ROW['prod_category'].'</center>';
			$sub_array[] = '<center>'.$supplier.'</center>';
			$sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] = '<center>'.$btnShow.' '.$btnDelete.'</center>';
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
?>
