<?php
  session_start();
  include_once('../inc/config.php');
  include_once('../inc/class.upload.php');
  include_once('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow('tbl_customer'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_customer` SET `cust_status` = 0 WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $status = $_POST['status']; 
    $clinic_id = $_POST['clinic_id'];
    $SQL = "SELECT `c`.*, `staff_fname` FROM `tbl_customer` AS `c` INNER JOIN `tbl_staff` AS `s` ON (`c`.`user_id` = `s`.`id`) 
      WHERE `c`.`clinic_id` = '$clinic_id'";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      0 => 'id',
      2 => 'cust_fname',
      3 => 'cust_gender',
      4 => 'cust_dob',
      5 => 'cust_contact',
      6 => 'cust_email',
      7 => 'cust_address',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`cust_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `c`.`id` LIKE '%".$search_value."%')";
    }
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
      $gender = ($ROW['cust_gender'] == 0) ? 'Female' : 'Male';
      $custId = 'P-'. sprintf('%05d', $ROW['id']);
      $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $status = ($ROW['cust_status'] == 1) ? '<span class="badge bg-primary">ACTIVE</span>' : '<span class="badge bg-secondary">INACTIVE</span>';
      $btnQr = '<a href="qr_generator.php?cid='.$ROW['cust_code'].'&file='.$ROW['cust_fname'].'" class="btn btn-icon btn-info qrBtn mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="QR Code"><span class="tf-icons bx bx-qr"></span></a>';
      $btnShow = '<a href="patient_personal.php?pgid='.$_POST['pgid'].'&cid='.$ROW['cust_code'].'" class="btn btn-icon btn-primary mb-1" data-bs-toggle="tooltip" data-bs-placement="top" title="View detail info"><span class="tf-icons bx bx-show"></span></a>';
      // edit
      // if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-outline-light editBtn"><span class="text-dark tf-icons bx bx-edit"></span></a>';
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-outline-light deleteBtn"><span class="text-dark tf-icons bx bx-trash"></span></a>';
      // }
      // else{
        // $btnEdit = "";
      // }
      
      // delete
      // if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
      //   $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      // }
      // else{
      //   $btnDelete = "";
      // }

      $sub_array = array();
      $sub_array[] = $custId;
      //$sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['cust_image'].'.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
      $sub_array[] = '<a href="../patients/patient_chart.php?pgid=11&pid='.$ROW["cust_code"].'&cid='.$ROW["cust_code"].'&apid=0"><div class="d-flex"><img src="../images/profiles/'.$folder.''.$ROW['cust_image'].'.jpg" alt="avatar" class="w-px-40 h-auto rounded-circle me-3" /><div><span class="">'.$ROW['cust_fname'].'</span><br><span class="text-muted">'.$ROW['cust_email'].'</span></div></div></a>';
      $sub_array[] = $gender;
      $sub_array[] = $age.'y';
      $sub_array[] = $ROW['cust_contact'];
      $sub_array[] = dentistToggle($ROW['id']);
      $sub_array[] = $status;
      $sub_array[] = ($ROW['cust_status'] == 1) ? $btnEdit.' '.$btnDelete : '';
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

  function dentistToggle($cid) {
    global $CON;
    $SQL = "SELECT `i`.`staff_id`, `staff_fname`, `staff_image` FROM `tbl_invoice_patient` AS `i` INNER JOIN `tbl_staff` AS `s` ON (`i`.`staff_id` = `s`.`id`) WHERE `i`.`cust_id` = '$cid' AND `inv_status` > 0 GROUP BY `i`.`staff_id` ORDER BY `i`.`timestamp` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $o .= '<ul class="list-unstyled m-0 d-flex align-items-center justify-content-start avatar-group">';
    if (mysqli_num_rows($QUERY) > 0) {
      while ($ROW = mysqli_fetch_assoc($QUERY)) {
        $name = $ROW['staff_fname'];
        $image = $ROW['staff_image'];
        $sid = 'S-'.sprintf('%05d', $ROW['staff_id']);
        $path = ($image == '0') ? '../images/profiles/0.jpg' : '../images/profiles/'.$sid.'/'.$image.'.jpg';
        $o .= '<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="'.$name.'" class="avatar pull-up">';
        $o .= '<img class="rounded-circle" src="'.$path.'" alt="Avatar">';
        $o .= '</li>';
      }
    } else {
      $SQL = "SELECT `dentist_id`, `staff_fname`, `staff_image` FROM `tbl_staff` AS `s` INNER JOIN `tbl_customer` AS `c` ON (`c`.`dentist_id` = `s`.`id`) WHERE `c`.`id` = '$cid' LIMIT 1";
      $QUERY = mysqli_query($CON, $SQL);
      $ROW = mysqli_fetch_assoc($QUERY);
      $name = $ROW['staff_fname'];
      $image = $ROW['staff_image'];
      $sid = 'S-'.sprintf('%05d', $ROW['dentist_id']);
      $path = ($image == '0') ? '../images/profiles/0.jpg' : '../images/profiles/'.$sid.'/'.$image.'.jpg';
      $o .= '<li data-bs-toggle="tooltip" data-popup="tooltip-custom" data-bs-placement="top" title="'.$name.'" class="avatar pull-up">';
      $o .= '<img class="rounded-circle" src="'.$path.'" alt="Avatar">';
      $o .= '</li>';
    }
    $o .= '</ul>';
    return $o;
  }

  function getLastId() {
    global $CON;
    $SQL = "SELECT `id` FROM `tbl_customer` ORDER BY `id` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnInsert() {
    global $CON;
    $clinic_id = $_POST['clinic_id'];
    $cid = $_POST['id'];
    $id = trim($cid, 'P-');
    $id = (int) $id;
    $code = $_POST['code'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $cont = str_replace(' ', '', $_POST['contact']);
    $contact = substr($cont,0,3).' '.substr($cont,3,3).' '.substr($cont,6);
    $email = $_POST['email'];
    $address = $_POST['address'];
    $member = $_POST['membership'];
    $datetime = $_POST['datetime'];
    $dentist = $_POST['dentist'];
    $med_history = '0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0';
    $uid = $_POST['uid'];
    $status = 1;
    if (!empty($_FILES['image']['name'])) {
    //   $uploadDir = '../images/profiles/'.$cid.'/';
      $uploadDir = $file_path.'profiles/'.$cid.'/';
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
    // $SQL = "INSERT INTO `tbl_customer` (`id`, `timestamp`, `cust_code`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_email`, `cust_address`, `cust_image`, `memb_id`, `med_history`, `user_id`, `dentist_id`, `cust_status`) 
    // VALUES ('$id', '$datetime', '$code', '$name', '$gender', '$dob', '$contact', '$email', '$address', '$fileName', '$member', '$med_history', '$uid', '$dentist', '$status')";
    // $QUERY = mysqli_query($CON, $SQL);

    $SQL = "INSERT INTO `tbl_customer` (`id`, `clinic_id`, `timestamp`, `cust_code`, `cust_fname`, `cust_gender`, `cust_dob`, `cust_contact`, `cust_email`, `cust_address`, `cust_image`, `memb_id`, `med_history`, `user_id`, `dentist_id`, `cust_status`)  VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $STMT = mysqli_prepare($CON, $SQL);
    mysqli_stmt_bind_param($STMT,'ssssssssssssssss', $id, $clinic_id, $datetime, $code, $name, $gender, $dob, $contact, $email, $address, $fileName, $member, $med_history, $uid, $dentist, $status);
    $QUERY = mysqli_stmt_execute($STMT);
    $last_id = mysqli_insert_id($CON);

    // mysqli_query($CON, "INSERT INTO `tbl_queue` (`appo_id`, `cust_id`, `staff_id`, `user_id`, `queue_duration`, `queue_status`) VALUES (1, '$last_id', 1, 1, 1, 1)");
    // $NOTIFICATION = mysqli_query($CON, "INSERT INTO `tbl_notification` (`cust_id`, `notify_id`, `user_id`) VALUES ('$last_id', 1, 1)");
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode(mysqli_stmt_debug_info($STMT));
  }

  function fnUpdate() {
    global $CON,$file_path; 
    $cid = $_POST['id'];
    $id = trim($cid, 'P-');
    $id = (int) $id;
    $code = $_POST['code'];
    $name = $_POST['name'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];
    $email = $_POST['email'];
    $cont = str_replace(' ', '', $_POST['contact']);
    $contact = substr($cont,0,3).' '.substr($cont,3,3).' '.substr($cont,6);
    $address = $_POST['address'];
    $member = $_POST['membership'];
    $dentist = $_POST['dentist'];
    
    $datetime = $_POST['datetime'];
    if (!empty($_FILES['image']['name'])) {
      $IMG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `cust_image` FROM `tbl_customer` WHERE `id` = '$id' LIMIT 1"));
      $image_old = $IMG['cust_image'];
    //   $image_old_path = '../images/profiles/'.$cid.'/'.$image_old.'.jpg';
      
      $uploadDir = $file_path.'profiles/'.$cid.'/'.$image_old.'.jpg';
      @unlink($image_old_path);
    //   $uploadDir = '../images/profiles/'.$cid.'/';
      $uploadDir = $file_path.'profiles/'.$cid.'/';
      
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
      
      $SQL = "UPDATE `tbl_customer` SET `id` = ?, `timestamp`=?, `cust_fname` = ?, `cust_gender` = ?, `cust_dob` = ?, `cust_contact` = ?, `cust_email` = ?, `cust_address` = ?,`cust_image` = ?, `memb_id` = ?, `dentist_id` = ? WHERE `cust_code` = ?";
      
      $stmt = mysqli_prepare($CON, $SQL);
      mysqli_stmt_bind_param($stmt,'ssssssssssss', $id, $datetime, $name, $gender, $dob, $contact, $email, $address, $fileName, $member, $dentist, $code);
      $QUERY = mysqli_stmt_execute($stmt);  
        
    } else {
      
      $SQL = "UPDATE `tbl_customer` SET `id` = ?, `timestamp`=?, `cust_fname` = ?, `cust_gender` = ?, `cust_dob` = ?, `cust_contact` = ?, `cust_email` = ?, `cust_address` = ?, `memb_id` = ?, `dentist_id` = ? WHERE `cust_code` = ?";
      
      $stmt = mysqli_prepare($CON, $SQL);
      mysqli_stmt_bind_param($stmt,'sssssssssss', $id, $datetime, $name, $gender, $dob, $contact, $email, $address, $member, $dentist,$code);
      $QUERY = mysqli_stmt_execute($stmt);  
    }
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
?>