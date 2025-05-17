<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow ('tbl_supplier'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_supplier` SET `supp_status` = 0 WHERE `id` = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $SQL = "SELECT `s`.*, `prod_category` FROM `tbl_supplier` AS `s` INNER JOIN `tbl_product_category` AS `c` ON (`s`.`exp_cate_id` = `c`.`id`) WHERE `supp_status` = 1";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      1 => 'id',
      2 => 'supp_fname',
      3 => 'exp_cate_id',
      4 => 'timestamp',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`supp_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `s`.`id` LIKE '%".$search_value."%')";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `s`.`timestamp` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $user_id = $ROW['user_id'];
      $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$user_id' LIMIT 1"));
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
      
      $suppid = 'SUP-'. sprintf('%05d', $ROW['id']);
      $folder = ($ROW['supp_image'] == '0') ? '' : $suppid.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $sub_array = array();
      $sub_array[] = '<center>'.($i += 1).'</center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['supp_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="100" width="100">'.'<h5 class="mt-2"><span class="badge bg-label-primary" style="width:100px">'.$suppid.'</span></h5></center>';
      $sub_array[] = '<h4 class="body-text mb-2">'.$ROW['supp_fname'].'</h4><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$ROW['supp_contact'].'<br><i class="bx bxs-map"></i> '.$ROW['supp_address'];
      $sub_array[] = '<center>'.$ROW['prod_category'].'</center>';
      $sub_array[] = '<center>'.$USER['staff_fname'].'<br>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$btnEdit.'<br>'.$btnDelete.'</center>';
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

  function fnInsert() {
    global $CON;
    $id = $_POST['id'];
    $code = $_POST['code'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $cont = str_replace(' ', '', $_POST['contact']);
    $contact = substr($cont,0,3).' '.substr($cont,3,3).' '.substr($cont,6);
    $address = $_POST['address'];
    $uid = $_POST['uid'];
    $status = 1;
    if (!empty($_FILES['image']['name'])) {
      $uploadDir = '../images/profiles/'.$id.'/';
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
    $SQL = "INSERT INTO `tbl_supplier` (`supp_code`, `supp_fname`, `exp_cate_id`, `supp_contact`, `supp_address`, `supp_image`, `user_id`, `supp_status`) VALUES ('$code', '$name', '$category', '$contact', '$address', '$fileName', '$uid', '$status')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function fnUpdate() {
    global $CON;
    $sid = $_POST['id'];
    $id = trim($sid, 'SUP-');
    $id = (int) $id;
    $code = $_POST['code'];
    $name = $_POST['name'];
    $category = $_POST['category'];
    $cont = str_replace(' ', '', $_POST['contact']);
    $contact = substr($cont,0,3).' '.substr($cont,3,3).' '.substr($cont,6);
    $address = $_POST['address'];
    if (!empty($_FILES['image']['name'])) {
      $IMG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `supp_image` FROM `tbl_supplier` WHERE `id` = '$id' LIMIT 1"));
      $image_old = $IMG['supp_image'];
      $image_old_path = '../images/profiles/'.$sid.'/'.$image_old.'.jpg';
      @unlink($image_old_path);
      $uploadDir = '../images/profiles/'.$sid.'/';
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
      $SQL = "UPDATE `tbl_supplier` SET `supp_code` = '$code', `supp_fname` = '$name', `exp_cate_id` = '$category', `supp_contact` = '$contact', `supp_address` = '$address', `supp_image` = '$fileName' WHERE id = '$id'";
    } else {
      $SQL = "UPDATE `tbl_supplier` SET `supp_code` = '$code', `supp_fname` = '$name', `exp_cate_id` = '$category', `supp_contact` = '$contact', `supp_address` = '$address' WHERE id = '$id'";
    }
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function getLastId() {
    global $CON;
    $SQL = "SELECT `id` FROM `tbl_supplier` ORDER BY `id` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
}
?>