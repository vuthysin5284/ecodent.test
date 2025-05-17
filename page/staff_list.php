<?php
  $page = 'Staff List';
  // include_once('../inc/session.php');
  session_start();
  $log = $_SESSION['uid'];
  $clinic_id = $_SESSION['business_id'];
  $lang = 1;
  include_once('../inc/config.php');
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
        $text_btn_create = 'New Employee';
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
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <!-- Heading -->  
              <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div>
                <div class="p-2">
                <!--?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?-->
                  <button type="button" id="create" class="btn btn-primary d-none">
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                      <i class="bx bx bx-plus d-block d-sm-none"></i>
                  </button>
                  <!--?php }?-->
                </div>
              </div>
              <!-- Modal -->
              <!-- <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle">< ?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> -->
                      
                <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 70%;" tabindex="-1" id="employeeOffcanvas" 
                  aria-labelledby="employeeOffcanvasLabel"> 
                  <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="employeeOffcanvasLabel"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                  <form  id="addForm" method="POST" enctype="multipart/form-data"> 
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
                      
                      <div class="offcanvas-footer" style="margin-bottom:50px;margin-top:50px;">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form> 
                </div>
              </div> 
                    <!-- </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form>
                </div> -->
                <div class="position-fixed progress w-100 top-0 left-0">
                  <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
              </div>
              
              <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center"><?php echo $th2; ?></th>
                          <th class="text-left w-100"><?php echo $th3; ?></th>
                          <th class="text-center"><?php echo $th4; ?></th>
                          <th class="text-center"><?php echo $th5; ?></th>
                          <th class="text-center"><?php echo $th6; ?></th>
                          <th class="text-center"><?php echo $th7; ?></th>
                          <th class="text-center"><?php echo $th8; ?></th>
                          <th class="text-center"><?php echo $th9; ?></th>
                          <th class="text-center"><?php echo $th10; ?></th>
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                    </table>
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
    <script src="../script/page_staff_list.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Employee List");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>
