<?php
  session_start();
	$log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
	if (!isset($log)) { header('Location: login.php'); }
	error_reporting(0);
  $sid = $_GET['sid'];
  $cid = $_GET['cid'];
  $pgid = $_GET['pgid']; 
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
        $page_category = 'Employee';
        $page_title = 'Staff Profile';
        $text_heading = 'Staff Profile';
        $text_edit_button = 'User Login';
        $text_description = 'We need your permissions to let this user navigate the system.';
        $text_content = 'PERMISSION';
        $text_action = 'ALLOW';
      } else {
        $page_category = 'បុគ្គលិក';
        $page_title = 'អ្នកប្រើប្រាស់ប្រព័ន្ធ';
        $text_heading = 'លក្ខខណ្ឌអនុញ្ញាត';
        $text_edit_button = 'កំណត់លេខសំងាត់';
        $text_description = 'អ្នកប្រើប្រាស់ត្រូវមានការអនុញ្ញាតដើម្បីអាចប្រើប្រាស់ប្រព័ន្ធបាន!';
        $text_content = 'មាតិការ';
        $text_action = 'អនុញ្ញាត';
      }
      include_once('../inc/config.php');
      include_once ('../inc/setting.php');
      include_once('../inc/header.php');
    ?>  
  </head>
  <body>
    <?php
      $SQL = "SELECT * FROM `tbl_staff` WHERE `id` = '$sid' LIMIT 1";
      $QUERY = mysqli_query($CON, $SQL);
      $ROW = mysqli_fetch_assoc($QUERY);
      $code = $ROW['staff_code'];
      $fname = $ROW['staff_fname'];
      $age = (date('Y') - date('Y', strtotime($ROW['staff_dob'])));
      $gender = ($ROW['staff_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $image = $ROW['staff_image'];
      $contact = $ROW['staff_contact'];
      $address = $ROW['staff_address'];
      $permission = $ROW['user_permission'];
      $permissions = explode($permission, ', ');

      $add = $ROW['user_add_perm'];
      $add = explode(', ',$add); 
      
      $edit = $ROW['user_edit_perm'];
      $edit = explode(', ',$edit);

      $delete = $ROW['user_delete_perm'];
      $delete = explode(', ',$delete);

      $StaffId = 'S-'.sprintf('%05d', $ROW['id']).'/';
      $folder = ($image == '0') ? '' : $StaffId.'/';
      $image_path = '../images/profiles/'.$folder.''.$image.'.jpg';
    ?>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <!-- Content -->
            <div class="container-xxl flex-grow-1 container-p-y">
              
              <div class="row"> 

                <div class="col-md-12">
                  <div class="card">

                    <?php include_once ('../inc/staff_menu.php'); ?>
                     
                    <div class="card-body"> 
                    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">  
                            <?php if($is_invoice==0){ ?>
                              <?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                                <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                                  <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Add Assistant</span>
                                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                                </button> 
                                <?php }?>
                              <?php }?> 
                          </div>  
                    <div class="d-flex flex-column flex-md-row p-4 gap-4 py-md-5 align-items-center justify-content-center">
                            <div class="list-group">
                              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                  <div>
                                    <h6 class="mb-0">List group item heading</h6>
                                    <p class="mb-0 opacity-75">Some placeholder content in a paragraph.</p>
                                  </div>
                                  <small class="opacity-50 text-nowrap">now</small>
                                </div>
                              </a>
                              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                  <div>
                                    <h6 class="mb-0">Another title here</h6>
                                    <p class="mb-0 opacity-75">Some placeholder content in a paragraph that goes a little longer so it wraps to a new line.</p>
                                  </div>
                                  <small class="opacity-50 text-nowrap">3d</small>
                                </div>
                              </a>
                              <a href="#" class="list-group-item list-group-item-action d-flex gap-3 py-3" aria-current="true">
                                <img src="https://github.com/twbs.png" alt="" width="32" height="32" class="rounded-circle flex-shrink-0">
                                <div class="d-flex gap-2 w-100 justify-content-between">
                                  <div>
                                    <h6 class="mb-0">Third heading</h6>
                                    <p class="mb-0 opacity-75">Some placeholder content in a paragraph.</p>
                                  </div>
                                  <small class="opacity-50 text-nowrap">1w</small>
                                </div>
                              </a>
                            </div>
                          </div>
                    </div> 
                  </div>
                </div>
              </div>
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
    <script src="../script/page_staff_permission.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Employee List");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active"); 
        
          // active tab 
          var activeTabMenu = document.getElementById("staff-benefit");
          activeTabMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>