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
              <h4 class="fw-bold py-3 mb-4"><?php echo '<span class="text-muted fw-light">'.$page_category.' / </span> '.$page_title; ?></h4>
              <div class="row">
                
                <?php include_once ('../inc/staff_menu.php'); ?>

                <div class="col-md-12">
                  <div class="card">
                    <div class="card-header d-flex align-items-center justify-content-between">


                      <h5><?php echo $text_heading; ?></h5>
                      <div class="dropdown">
                        <button class="btn p-0" type="button" id="transactionID" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID" style="">
                          <a class="dropdown-item" href="javascript:void(0);" id="editBtn"><?php echo $text_edit_button; ?></a>
                        </div>
                      </div>
                    </div>


                    <!-- Modal -->
                    <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                      <div class="modal-dialog">
                        <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                          <div class="modal-header">
                            <h5 class="modal-title" id="backDropModalTitle">User Account</h5>
                            <button
                              type="button"
                              class="btn-close"
                              data-bs-dismiss="modal"
                              aria-label="Close"
                            ></button>
                          </div>
                          <div class="modal-body">
                            <div class="row mb-3"><?php echo inpText('User Id', 'id', '', '2'); ?></div>
                            <div class="row mb-3"><?php echo inpText('Username', 'username', 'Username'); ?></div>
                            <div class="row mb-3"><?php echo inpPwd('Password', 'password','3'); ?></div>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal"> Close</button>
                            <button type="submit" class="btn btn-primary">Save</button>
                          </div>
                        </form>
                      </div>
                    </div>
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
                    <hr class="my-0" />
                    <form id="addForm" method="POST" enctype="multipart/form-data">
                      <div class="card-body">
                        <span><?php echo $text_description; ?></span>
                        <div class="error"></div>
                      </div>
                      <div class="table-responsive">
                        <table class="table table-striped table-borderless border-bottom">
                          <thead>
                            <tr>
                              <th class="text-wrap text-center">#</th>
                              <th class="text-wrap w-75"><?php echo $text_content; ?></th>
                              <th class="text-wrap text-center"><?php echo $text_action; ?></th> 
                              <th class="text-wrap text-center">ADD</th>
                              <th class="text-wrap text-center">EDIT</th>
                              <th class="text-wrap text-center">DELETE</th>
                            </tr>
                          </thead>
                          <tbody id="permissionRows">
                          </tbody>
                        </table>
                        <input type="hidden" id="sid" name="sid" value="<?php echo $sid; ?>" class="form-control" readonly/>
                      </div>
                      <div class="card-footer">
                        <button type="submit" class="btn btn-primary me-2">Save</button> </div>
                      </div>
                    </form>
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
          var activeTabMenu = document.getElementById("staff-invoice");
          activeTabMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>