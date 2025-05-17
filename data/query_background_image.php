<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnInsert(); }
  else if ($qid == 2) { fnGetImage(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $IMG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_background_image` WHERE `id` = '$id'"));
    $fileName = $IMG['file_image'];
    $SQL = "DELETE FROM `tbl_background_image` WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    if ($delQuery == TRUE) {
      $data = array('status' => 'success');
      $image_path = '../images/backgrounds/'.$fileName;
      @unlink($image_path);
    } else {
      $data = array('status' => 'failed');
    }
    echo json_encode($data);
  }
  
  function fnInsert() {
    global $CON;
    if (array_sum($_FILES['files']['size']) > 0) {
      foreach ($_FILES['files']['tmp_name'] as $key => $value ) {
        $file_type = $_FILES['files']['type'][$key];
        $allowed_type = "image/jpeg, jpg, gif, image/png";
        $allowed_path = explode(", ", $allowed_type);
        if (in_array($file_type, $allowed_path)) {
          $uploadDir = '../images/backgrounds/';
          $handle = new Upload($_FILES['files']['tmp_name'][$key]);
          $fileName = date('sihjmY').$key;
          $handle->image_resize          = true;
          $handle->image_ratio_x         = true;
          $handle->image_y               = 1920;
          $handle->image_x               = 1080;
          $handle->jpeg_quality          = 72;
          $handle->image_text_background = 2;          
          $handle->file_new_name_ext     = 'jpg';
          $handle->file_new_name_body    = $fileName;
          $handle->Process($uploadDir); 
          $str = $fileName.'.jpg';
          $sql = "INSERT INTO `tbl_background_image` (`file_image`) VALUES ('$str')";
          $query = mysqli_query($CON, $sql);
          $data = ($query == TRUE) ? array('status' => 'true') : array('status' => 'false');
        }
      }
    }
    echo json_encode($data);
  }

  function fnGetImage() {
    global $CON;
    $SQL = "SELECT * FROM `tbl_background_image`";
    $QUERY = mysqli_query($CON, $SQL);
    $o = '';
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $img = $ROW['file_image'];
      $o .= '<div class="col-4 position-relative" z-index="100">';
      $o .=   '<a href="../images/backgrounds/'.$img.'" data-lightbox="patient-img"><img src="../images/backgrounds/'.$img.'" class="img-thumbnail d-block rounded mb-2 p-0" style="width: 100%; height:100px; object-fit: cover;"></a>';
      $o .=   '<div class="position-absolute top-0 end-0 mt-1 me-1">';
      $o .=     '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger p-0 deleteBtn"><i class="bx bx-trash"></i></a>';
      $o .=   '</div>';
      $o .= '</div>';
    }
    echo $o;
  }

?>