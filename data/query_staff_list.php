<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");

  $qid = $_POST['qid'];  
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow ('tbl_staff'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else { }
  
   function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_staff` SET `staff_status` = 0 WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $SQL = "SELECT `s`.*, `staff_position` FROM `tbl_staff` AS `s` INNER JOIN `tbl_staff_position` AS `p` ON (`s`.`staff_position_id` = `p`.`id`) WHERE `staff_status` = 1";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      0 => 'id',
      2 => 'staff_fname',
      3 => 'staff_gender',
      4 => 'staff_dob',
      5 => 'staff_position_id',
      6 => 'staff_contact',
      7 => 'staff_salary',
      8 => 'staff_commission',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`staff_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `s`.`id` LIKE '%".$search_value."%')";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `s`.`id` ASC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      if ($ROW['id'] > 1) {
        $uid = $ROW['user_id'];
        $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname`, `staff_position_id` FROM `tbl_staff` AS `s` INNER JOIN `tbl_staff_position` AS `p` ON (`s`.`staff_position_id` = `p`.`id`) WHERE `s`.`id` = '$uid' LIMIT 1"));
        $age = (date('Y') - date('Y', strtotime($ROW['staff_dob'])));
        $gender = ($ROW['staff_gender'] == 0) ? 'F' : 'M';
        $btnShow = '<a href="staff_permission.php?pgid=17&sid='.$ROW['id'].'" class="btn btn-icon btn-primary showBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Setting"><span class="tf-icons bx bx-cog"></span></a>';
        // edit
        if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
          $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></span></a>';
        }
        else{
          $btnEdit = "";
        }
        // delete
        if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
          $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
        }
        else{
          $btnDelete = "";
        }
        
        $StaffId = 'S-'. sprintf('%05d', $ROW['id']);
        $folder = ($ROW['staff_image'] == '0') ? '' : $StaffId.'/';
        $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
        $sub_array = array();
        $sub_array[] = '<center>'.$i = ($i + 1).'<center>';
        $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['staff_image']. '.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
        $sub_array[] = $ROW['staff_fname'];
        $sub_array[] = '<center>'.$gender.'</center>';
        $sub_array[] = '<center>'.$age.'</center>';
        $sub_array[] = '<center>'.$ROW['staff_position'].'</center>';
        $sub_array[] = '<center>'.$ROW['staff_contact'].'</center>';
        $sub_array[] = '<center>'.$ROW['staff_salary'].'</center>';
        $sub_array[] = '<center>'.$ROW['staff_commission'].'</center>';
        $sub_array[] = '<center>'.$btnShow.' '.$btnEdit.' '.$btnDelete.'</center>';
        $data[] = $sub_array;
      }
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered' => $total_all_rows,
      'data'=> $data,
    );
    echo  json_encode($output);
  }

  function fnInsert() {
    global $CON;
    $id = $_POST['id'];
    $code = $_POST['code'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $system_id = $_SESSION["system_id"];
    $username = $id;
    $dob = $_POST['dob'];
    $cont = str_replace(' ', '', $_POST['contact']);
    $password = password_hash($cont, PASSWORD_DEFAULT);
    $contact = substr($cont,0,3).' '.substr($cont,3,3).' '.substr($cont,6);
    $address = $_POST['address'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $commission = $_POST['commission'];
    $PERM = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `default_permission` FROM `tbl_staff_position` WHERE `id` = '$position'"));
    $default_permission = $PERM['default_permission']; 
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
      $handle->file_new_name_ext      = 'jpg';
      $handle->file_new_name_body     = $fileName;
      $handle->Process($uploadDir); 
    } else { $fileName = '0'; }
    $SQL = "INSERT INTO `tbl_staff` (`system_id`,`staff_code`, `staff_fname`, `staff_gender`, `staff_dob`, `staff_contact`, `staff_address`, `staff_image`, 
    `staff_position_id`, `staff_salary`, `staff_commission`, `username`, `password`, `user_permission`, `user_lang`, `user_id`, `staff_status`,user_add_perm,user_edit_perm,user_delete_perm) 
    VALUES (?,?,?,?,?, ?,?,?,?,?, ?,?,?,?,?, ?,?, ?,?,?)"; 

    $user_lang = 1;
    $stmt = mysqli_prepare($CON, $SQL);
    mysqli_stmt_bind_param($stmt,'ssssssssssssssssssss', $system_id,$code,$name, $gender,$dob,$contact,$address,$fileName,$position,$salary,$commission,$username,$password,$default_permission,$user_lang,$uid,$status,$default_permission,$default_permission,$default_permission);
    $QUERY = mysqli_stmt_execute($stmt);  

    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
  
  function fnUpdate() {
    global $CON;
    $sid = $_POST['id'];
    $id = trim($sid, 'S-');
    $id = (int) $id;
    $code = $_POST['code'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $cont = str_replace(' ', '', $_POST['contact']);
    $contact = substr($cont,0,3).' '.substr($cont,3,3).' '.substr($cont,6);
    $address = $_POST['address'];
    $position = $_POST['position'];
    $salary = $_POST['salary'];
    $commission = $_POST['commission'];
    if (!empty($_FILES['image']['name'])) {
      $IMG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_image` FROM `tbl_staff` WHERE `id` = '$id' LIMIT 1"));
      $image_old = $IMG['staff_image'];
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
      $SQL = "UPDATE `tbl_staff` SET `staff_code` = '$code', `staff_fname` = '$name', `staff_gender` = '$gender', `staff_dob` = '$dob', `staff_contact` = '$contact', `staff_address` = '$address', `staff_image` = '$fileName', `staff_position_id` = '$position', `staff_salary` = '$salary', `staff_commission` = '$commission' WHERE id = '$id'";
    } else {
      $SQL = "UPDATE `tbl_staff` SET `staff_code` = '$code', `staff_fname` = '$name', `staff_gender` = '$gender', `staff_dob` = '$dob', `staff_contact` = '$contact', `staff_address` = '$address', `staff_position_id` = '$position', `staff_salary` = '$salary', `staff_commission` = '$commission' WHERE id = '$id'";
    }
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
  
  function getLastId() {
    global $CON;
    $SQL = "SELECT `id` FROM `tbl_staff` ORDER BY `id` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }
?>