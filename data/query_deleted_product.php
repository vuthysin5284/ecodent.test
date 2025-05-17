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
    $SQL = "DELETE FROM `tbl_product` WHERE `id` = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
	}

  function fnRecovery() {
    global $CON;
		$id = $_POST['id'];
    $SQL = "UPDATE `tbl_product` SET `prod_status` = 1 WHERE `id` = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
	}

	function fnTable() {
    global $CON;
    $SQL = "SELECT `p`.*, `prod_category`, `supp_fname` FROM `tbl_product` AS `p` INNER JOIN `tbl_product_category` AS `pc` ON (`p`.`prod_cate_id` = `pc`.`id`) INNER JOIN `tbl_supplier` AS `s` ON (`p`.`supp_id` = `s`.`id`) WHERE `prod_status` = 0";
    $output = array();
    $columns = array(
      0 => 'id',
      2 => 'prod_description',
      3 => 'prod_category',
      4 => 'supp_fname',
      5 => 'prod_qty',
      6 => 'prod_unit_cost',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`prod_description` LIKE '%". $search_value ."%'";
      $SQL .= " OR `p`.`id` LIKE '%".$search_value."%')";
    }
    $SQL .= " GROUP BY `p`.`id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `p`.`timestamp` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $uid = $ROW['user_id'];
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
      $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary mb-2 recoveryBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore"><span class="tf-icons bx bx-history"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger mb-2 deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      $prodid = 'PRO-'. sprintf('%05d', $ROW['id']);
      $folder = ($ROW['prod_image'] == '0') ? '' : $prodid.'/';
      $sub_array = array();
      $sub_array[] = '<center>'.$prodid.'</center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['prod_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="50" width="50"></center>';
      $sub_array[] = $ROW['prod_description'];
      $sub_array[] = '<center>'.$ROW['prod_category'].'</center>';
      $sub_array[] = '<center>'.$ROW['supp_fname'].'</center>';
      $sub_array[] = '<center>'.$ROW['prod_qty'].'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($ROW['prod_unit_cost'],2).'</span></div>';
      $sub_array[] = '<center>'.$btnShow.' '.$btnDelete.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw'=> intval($_POST['draw']),
      'recordsTotal' =>$count_rows ,
      'recordsFiltered'=>   $total_all_rows,
      'data'=>$data,
    );
    echo  json_encode($output);
  }
?>