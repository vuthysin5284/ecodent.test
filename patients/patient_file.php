<?php
  session_start();
  $page = 'Images';
  $log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  error_reporting(0);
  $cid = $_GET['cid'];
  $fid = $_GET['fid'];
  $pgid = $_GET['pgid']; 
  $apid = $_GET['apid']; 
  ?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <?php
      if ($lang == 1) {
        $page_category = 'Patients';
        $page_title = 'Image';
        $text_btn_create = 'New';
        $text_form = 'Image';
        $m1 = 'Image Files';
      } else {
        $page_category = 'អតិថិជន';
        $page_title = 'រូបភាព';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'រូបភាព';
        $m1 = 'រូបភាព';
      }
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
    <?php
      $CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_customer` WHERE `cust_code` = '$cid' LIMIT 1"));
      $id = $CUST['id'];
      $code = $CUST['cust_code'];
      $fname = $CUST['cust_fname'];
      $age = (date('Y') - date('Y', strtotime($CUST['cust_dob'])));
      $custId = 'P-'. sprintf('%05d', $CUST['id']);
      $folder = ($CUST['cust_image'] == '0') ? '' : $custId.'/';
      $gender = ($CUST['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $image = $CUST['cust_image'];
      $contact = $CUST['cust_contact'];
      $address = $CUST['cust_address'];
      $image_path = '../images/profiles/'.$folder.''.$image.'.jpg';
    ?>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">  
              <div class="row">
                <div class="col-md-12">
                  <div class="card mb-4">
                    <div class="card-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <?php
                          echo '<img src="'.$image_path.'" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar"/>';
                          echo '<div class="button-wrapper">';
                          echo '<h4 class="body-text mb-2">'.$fname.'</h4>'.$gender.'&nbsp;&nbsp; - &nbsp;&nbsp;'.$age.' Years <br><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$contact.'<br><i class="bx bxs-map"></i> '.$address;
                          echo '</div>';
                        ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="d-flex justify-content-between">
                    <div class="me-auto">
                      <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                      </h4>
                    </div>
                    <div class="py-2">
                    <?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                      <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                        <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                          <i class="bx bx bx-plus d-block d-sm-none"></i>
                      </button>
                      <?php }?>
                    </div>
                  </div>
                  <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                    <div class="modal-dialog">
                      <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                        <div class="modal-header">
                          <h5 class="modal-title" id="backDropModalTitle"><?php echo $text_form; ?></h5>
                          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                          <div class="row mb-3">
                            <input type="hidden" id="fid" name="fid" value="<?php echo $fid; ?>" class="form-control" readonly/>
                            <input type="hidden" id="cid" name="cid" value="<?php echo $cid; ?>" class="form-control" readonly/>
                            <div class="col">
                                <label for="files" class="form-label"><?php echo $m1; ?></label>
                                <input class="form-control" type="file" id="files" name="files[]" multiple />
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                      </form>
                    </div>
                    <div class="position-absolute progress top-0 left-0 w-100">
                      <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                    </div>
                  </div>
                  <div class="card">
                    <div class="card-body">
                      <div class="row" id="imageData"></div>
                    </div>
                  </div>
                </div>
              </div>            
              <?php include_once ('../inc/patient_menu.php'); ?>
            </div>
            <?php include_once('../inc/footage.php'); ?> 
          </div>
        </div>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?> 
    <script src="../script/page_main.js"></script>
    <script src="../script/page_patient_file.js"></script>
    <script type="text/javascript">
      window.onload = function(){
          var activeSubMenu = document.getElementById("patient-files");
          activeSubMenu.classList.add("active");
      };
    </script>
  </body>
</html>