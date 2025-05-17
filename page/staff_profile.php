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
        $page_category = 'Employees';
        $page_title = 'Employee List';
        $text_btn_create = 'New';
        $text_form = 'Emp Information';
        $text_form_description = '* Employee\'s ID, and Qr Code is auto generated!';
        $m1 = 'Emp ID';
        $m2 = 'Qr Code';
        $m3 = 'Position';
        $m4 = 'Full Name';
        $m5 = 'Gender';
        $m6 = 'DOB';
        $m7 = 'Contact';
        $m8 = 'Address';
        $m9 = 'Basic Salary ($)';
        $m10 = 'Commission (%)';
        $th2 = 'Image';
        $th3 = 'Full Name';
        $th4 = 'Sex';
        $th5 = 'Age';
        $th6 = 'Position';
        $th7 = 'Contact';
        $th8 = 'Salary ($)';
        $th9 = 'Commission (%)';
        $th10 = 'Action';
      } else {
        $page_category = 'បុគ្គលិក';
        $page_title = 'បញ្ជីបុគ្គលិក';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'ពត៌មានបុគ្គលិក';
        $text_form_description = '* លេខកូដ និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
        $m1 = 'លេខកូដ';
        $m2 = 'Qr Code';
        $m3 = 'តួនាទី';
        $m4 = 'ឈ្មោះបុគ្គលិក';
        $m5 = 'ភេទ';
        $m6 = 'ថ្ងៃខែឆ្នាំកំណើត';
        $m7 = 'លេខទូរស័ព្ទ';
        $m8 = 'អាស័យដ្ឋាន';
        $m9 = 'ប្រាក់បៀរវត្ស ($)';
        $m10 = 'ភាគលាភ (%)';
        $th2 = 'រូបភាព';
        $th3 = 'ឈ្មោះបុគ្គលិក';
        $th4 = 'ភេទ';
        $th5 = 'អាយុ';
        $th6 = 'តួនាទី';
        $th7 = 'លេខទូរស័ព្ទ';
        $th8 = 'ប្រាក់ខែ ($)';
        $th9 = 'ភាគលាភ (%)';
        $th10 = 'ដំណើរការ';
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
                    <div class="card-body" style="width:80%; margin:auto">   
                      <?php echo inpImage('image', '../images/profiles/0.jpg'); ?>
                      <div class="row mb-3"> 
                        <?php echo inpText($m1, 'id', '','2'); ?>
                        <?php echo inpText($m2, 'code', '','2'); ?>
                        <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_description; ?></div>
                      </div>
                      <div class="row mb-3"><?php echo selectData($m3, 'position', 'SELECT * FROM `tbl_staff_position`', 'staff_position'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m4, 'name', 'Ex: Horn Panha'); ?></div>
                      <div class="row mb-3"> 
                        <?php echo selectGender($m5, 'gender'); ?>
                        <?php echo inpDate($m6, 'dob', ''); ?>
                      </div>
                      <div class="row mb-3"><?php echo inpText($m7, 'contact', 'Ex: 012 345 678'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m8, 'address', 'Ex: Toul Kork, Phnom Penh'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m9, 'salary', 'Ex: 1200'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m10, 'commission', 'Ex: 20'); ?></div> 
<!--  -->
                      <div class="card-footer"> 
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary">Save</button>
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
          var activeTabMenu = document.getElementById("staff-profile");
          activeTabMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>