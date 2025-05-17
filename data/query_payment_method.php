<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow ('tbl_payment_method'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else { }
  
   function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "DELETE FROM `tbl_payment_method` WHERE `id` = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $SQL = "SELECT * FROM `tbl_payment_method`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      2 => 'payment_method',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " WHERE `payment_method` LIKE '%". $search_value ."%'";
    }
    if (isset($_POST['order'])) {
    	$column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `payment_method` ASC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
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
      
      $StaffId = 'M-'. sprintf('%05d', $ROW['id']);
      $folder = ($ROW['method_image'] == '0') ? '' : $StaffId.'/';
      $sub_array = array();
      $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['method_image'].'.jpg" alt="user-avatar" class="d-block rounded mt-2" height="50" width="50" ></center>';
      $sub_array[] = $ROW['payment_method'];
      $sub_array[] = '<center>'.$btnEdit.' '.$btnDelete.'</center>';
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
    $id = 'M-'. sprintf('%05d', $_POST['id']);
    $method = $_POST['method'];
    if (!empty($_FILES['image']['name'])) {
      $uploadDir = '../images/profiles/'.$id.'/';
      $handle = new Upload($_FILES['image']); 
      $fileName = date('sihjmY');
      $handle->image_resize          = true;
      $handle->image_ratio_x         = true;
      $handle->image_y               = 800;
      $handle->image_x               = 600;
      $handle->jpeg_quality          = 80;              
      $handle->file_new_name_ext = 'jpg';
      $handle->file_new_name_body = $fileName;
      $handle->Process($uploadDir); 
    } else { $fileName = '0'; }
    $SQL = "INSERT INTO `tbl_payment_method` (`method_image`, `payment_method`) VALUES ('$fileName', '$method')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $sid = 'M-'. sprintf('%05d', $_POST['id']);
    $method = $_POST['method'];
    if (!empty($_FILES['image']['name'])) {
      $IMG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_image` FROM `tbl_staff` WHERE `id` = '$id' LIMIT 1"));
      $image_old = $IMG['method_image'];
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
      $SQL = "UPDATE `tbl_payment_method` SET `method_image` = '$fileName', `payment_method` = '$method' WHERE `id` = '$id'";
    } else {
        $SQL = "UPDATE `tbl_payment_method` SET `payment_method` = '$method' WHERE `id` = '$id'";
    }
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function getLastId() {
    global $CON;
    $SQL = "SELECT `id` FROM `tbl_payment_method` ORDER BY `id` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }
?>