<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDeleteImage(); }
  else if ($qid == 1) { fnInsert(); }
  else if ($qid == 2) { getImage(); }
  else { }
  
  function fnDeleteImage() {
    global $CON;
    $id = $_POST['id'];
    $IMG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_files_image` WHERE `id` = '$id'"));
    $fileName = $IMG['files_image'];
    $folder = 'P-'. sprintf('%05d', $IMG['cust_id']).'/';
    $SQL = "DELETE FROM `tbl_files_image` WHERE `id` = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    if ($delQuery == TRUE) {
      $data = array('status' => 'success');
      $image_path = '../images/profiles/'.$folder.''.$fileName.'.jpg';
      @unlink($image_path);
    } else {
      $data = array('status' => 'failed');
    }
    echo json_encode($data);
  }

  function getImage() {
    global $CON;
    $cid = $_POST['cid'];
    $fid = $_POST['fid'];
    $uid = $_POST['uid'];
    $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $SQL = "SELECT `fi`.`id`, `fi`.`user_id`, `files_image`, `cust_id` FROM `tbl_files_image` AS `fi` INNER JOIN `tbl_customer` AS `c` ON (`fi`.`cust_id` = `c`.`id`) INNER JOIN `tbl_staff` AS `s` ON (`fi`.`user_id` = `s`.`id`) WHERE `cust_code` = '$cid' AND `files_categ_id` = '$fid'";
    $QUERY = mysqli_query($CON, $SQL);
    $o = '';
    if (mysqli_num_rows($QUERY) > 0) {
      while ($ROW = mysqli_fetch_assoc($QUERY)) {
        $img = $ROW['files_image'];
        $folder = 'P-'. sprintf('%05d', $ROW['cust_id']);
        $o .= '<div class="col-md-2 col-6 position-relative" z-index="100">';
        $o .= '<a href="../images/profiles/'.$folder.'/'.$img.'.jpg" data-lightbox="patient-img"><img src="../images/profiles/'.$folder.'/'.$img.'.jpg" class="img-thumbnail d-block rounded mb-2 p-0" style="width: 100%; height:150px; object-fit: cover;"></a>';
        if ($USER['staff_position_id'] == 1 || $ROW['user_id'] == $uid) {
          $o .= '<div class="position-absolute top-0 end-0 mt-1 me-1">';
          $o .= '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger p-0 deleteImage"><i class="bx bx-trash"></i></a>';
          $o .= '</div>';
        }
        $o .= '</div>';
      }
    echo $o;
    }
  }

  function fnInsert() {
    global $CON;
    $cid = $_POST['cid'];
    $fid = $_POST['fid'];
    $uid = $_POST['uid'];
    $status = 1;
    if (array_sum($_FILES['files']['size']) > 0) {
        $CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_customer` WHERE `cust_code` = '$cid'"));
        $cust_id = 'P-'. sprintf('%05d', $CUST['id']);
        foreach ($_FILES['files']['tmp_name'] as $key => $value ) {
            $file_type = $_FILES['files']['type'][$key];
            $allowed_type = "image/jpeg, jpg, gif, image/png";
            $allowed_path = explode(", ", $allowed_type);
            if (in_array($file_type, $allowed_path)) {
                $uploadDir = '../images/profiles/'.$cust_id.'/';
                $handle = new Upload($_FILES['files']['tmp_name'][$key]);
                $fileName = date('sihjmY').$key;
                $handle->image_resize          = true;
                $handle->image_ratio_x         = true;
                $handle->image_y               = 1280;
                $handle->image_x               = 720;
                $handle->jpeg_quality          = 80;
                $handle->image_text_background = 2;          
                $handle->file_new_name_ext     = 'jpg';
                $handle->file_new_name_body    = $fileName;
                $handle->Process($uploadDir); 
                $cids = $CUST['id'];
                $sql = "INSERT INTO `tbl_files_image` (`files_image`, `cust_id`, `user_id`, `files_categ_id`, `files_image_status`) VALUES ('$fileName', '$cids', '$uid', '$fid', '$status')";
                $query = mysqli_query($CON, $sql);
                $data = ($query == TRUE) ? array('status' => 'true') : array('status' => 'false');
            }
        }
    }
    echo json_encode($data);
  }
?>