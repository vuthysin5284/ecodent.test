<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow ('tbl_product'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else { }
  
	function fnDelete() {
    global $CON;
		$id = $_POST['id'];
    $SQL = "UPDATE `tbl_product` SET `prod_status` = 0 WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
	}

	function fnTable() {
    global $CON;
    $status = $_POST['status'];
    $num_code = $_POST['num_code'];
    $SQL = "SELECT `p`.*, (`prod_qty` * `prod_unit_cost`) AS `total`, `prod_category`, `supp_fname` 
      FROM `tbl_product` AS `p` 
      INNER JOIN `tbl_product_category` AS `pc` ON (`p`.`prod_cate_id` = `pc`.`id`) 
      INNER JOIN `tbl_supplier` AS `s` ON (`p`.`supp_id` = `s`.`id`) 
      WHERE ('$status' = 'ALL'or `p`.`prod_status` = '$status')
      AND ('$num_code' = 'ALL'or `pc`.`prod_category` = '$num_code')";
      
    $totalQuery = mysqli_query($CON, $SQL);
    $total_all_rows = mysqli_num_rows($totalQuery);
    $output = array();
    $columns = array(
      1 => 'id',
      2 => 'prod_description',
      3 => 'prod_qty',
      4 => 'prod_unit_cost',
      5 => 'total',
      6 => 'prod_min_qty',
      7 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`prod_description` LIKE '%". $search_value ."%'";
      $SQL .= " OR `p`.`id` LIKE '%".$search_value."%')";
    }
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
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning mb-2 editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger mb-2 deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      }
      
      $prodid = 'PRO-'. sprintf('%05d', $ROW['id']);
      $folder = ($ROW['prod_image'] == '0') ? '' : $prodid.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $qty = $ROW['prod_qty'];
      $cost = $ROW['prod_unit_cost'];
      $total = $qty * $cost;
      $sub_array = array();
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['prod_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="100" width="100">'.'<h5 class="mt-2"><span class="badge bg-label-primary" style="width:100px">'.$prodid.'</span></h5></center>';
      $sub_array[] = '<h4 class="body-text mb-2">'.$ROW['prod_description'].'</h4><i class="bx bx-package"></i> '.$ROW['prod_category'].'<br><i class="bx bx-store"></i> '.$ROW['supp_fname'];
      $sub_array[] = '<center>'.$qty.'</center>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($cost,2).'</span></div>';
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($total,2).'</span></div>';
      $sub_array[] = '<center>'.$ROW['prod_min_qty'].'</center>';
      $sub_array[] = '<center>'.$USER['staff_fname'].'<br>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$btnEdit.'<br>'.$btnDelete.'</center>';
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

	function fnInsert() {
    global $CON,$file_path;
		$id = $_POST['id'];
    $code = $_POST['code'];
		$description = $_POST['description'];
		$categ = $_POST['categ'];
		$qty = $_POST['qty'];
		$cost = $_POST['cost'];
		$min = $_POST['min'];
		$suppid = $_POST['suppid'];
    $uid = $_POST['uid'];
    $status = 1;
    if (!empty($_FILES['image']['name'])) {
    //   $uploadDir = '../images/profiles/'.$id.'/';
      $uploadDir = $file_path.'/profiles/'.$cid.'/';
      $handle = new Upload($_FILES['image']); 
      $fileName = date('sihjmY');
      $handle->image_resize          = true;
      $handle->image_ratio_x         = true;
      $handle->image_y               = 800;
      $handle->image_x               = 800;
      $handle->jpeg_quality          = 80;              
      $handle->file_new_name_ext = 'jpg';
      $handle->file_new_name_body = $fileName;
      $handle->Process($uploadDir); 
    } else { $fileName = '0'; }
    $SQL = "INSERT INTO `tbl_product` (`prod_code`, `prod_description`, `prod_cate_id`, `prod_qty`, `prod_unit_cost`, `prod_image`, `prod_min_qty`, `supp_id`, `user_id`, `prod_status`) VALUES ('$code', '$description', '$categ', '$qty', '$cost', '$fileName', '$min', '$suppid', '$uid', '$status')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
	}

	function fnUpdate() {
    global $CON;
    $pid = $_POST['id'];
    $id = trim($pid, 'PRO-');
    $id = (int) $id;
    $code = $_POST['code'];
		$description = $_POST['description'];
		$categ = $_POST['categ'];
		$qty = $_POST['qty'];
		$cost = $_POST['cost'];
		$min = $_POST['min'];
		$suppid = $_POST['suppid'];
    $uid = $_POST['uid'];
    if (!empty($_FILES['image']['name'])) {
      $IMG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `prod_image` FROM `tbl_product` WHERE `id` = '$id' LIMIT 1"));
      $image_old = $IMG['prod_image'];
      $image_old_path = $file_path.'/profiles/'.$pid.'/'.$image_old.'.jpg'; 
      @unlink($image_old_path);
      $uploadDir = $file_path.'/profiles/'.$pid.'/';
      $handle = new Upload($_FILES['image']); 
      $fileName = date('sihjmY');
      $handle->image_resize          = true;
      $handle->image_ratio_x         = true;
      $handle->image_y               = 800;
      $handle->image_x               = 800;
      $handle->jpeg_quality          = 80;    
      $handle->file_overwrite        = true;   
      $handle->file_new_name_ext = 'jpg';
      $handle->file_new_name_body = $fileName;
      $handle->Process($uploadDir); 
      $SQL = "UPDATE `tbl_product` SET `prod_code` = '$code', `prod_description` = '$description', `prod_cate_id` = '$categ', `prod_qty` = '$qty', `prod_unit_cost` = '$cost', `prod_min_qty` = '$min', `prod_image` = '$fileName', `supp_id` = '$suppid' WHERE id = '$id'";
    } else {
      $SQL = "UPDATE `tbl_product` SET `prod_code` = '$code', `prod_description` = '$description', `prod_cate_id` = '$categ', `prod_qty` = '$qty', `prod_unit_cost` = '$cost', `prod_min_qty` = '$min', `supp_id` = '$suppid' WHERE id = '$id'";
    }
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

	function getLastId() {
    global $CON;
		$SQL = "SELECT `id` FROM `tbl_product` ORDER BY `id` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
	}

?>