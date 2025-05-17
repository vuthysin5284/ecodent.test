<?php
  $page = 'Patients';
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
        $page_category = 'Patients';
        $page_title = 'Patient List';
        $text_btn_create = 'New Patient';
        $text_form = 'Patient Information';
        $text_form_description = '* Patient\'s ID, and Qr Code is auto generated!';
        $m1 = 'Patient ID';
        $m2 = 'Qr Code';
        $m3 = 'Membership';
        $m4 = 'Full Name';
        $m5 = 'Gender';
        $m6 = 'Date of Birth';
        $m7 = 'Contact';
        $m8 = 'Address';
        $m9 = 'Dentist';
        $m10 = 'Register Date';
        $m11 = 'Email';
        $m12 = 'Age';
        $th2 = 'Image';
        $th3 = 'Full Name';
        $th4 = 'Sex';
        $th5 = 'Age';
        $th6 = 'Contact';
        $th7 = 'Address';
        $th8 = 'Dentist';
        $th9 = 'Action';
        $th10 = 'Email';
      } else {
        $page_category = 'អតិថិជន';
        $page_title = 'បញ្ជីអតិថិជន';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'បញ្ចូលពត៌មានអតិថិជន';
        $text_form_description = '* លេខកូដ និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
        $m1 = 'លេខកូដ';
        $m2 = 'Qr Code';
        $m3 = 'កញ្ចប់សមាជិក';
        $m4 = 'ឈ្មោះអតិថិជន';
        $m5 = 'ភេទ';
        $m6 = 'ថ្ងៃខែឆ្នាំកំណើត';
        $m12 = 'អាយុ';
        $m7 = 'លេខទូរស័ព្ទ';
        $m8 = 'អាស័យដ្ឋាន';
        $m9 = 'ទន្តបណ្ឌិត';
        $m10 = 'កាលបរិច្ឆេទ';
        $m11 = 'អ៊ីម៉ែល';
        $th2 = 'រូបភាព';
        $th3 = 'ឈ្មោះអតិថិជន';
        $th4 = 'ភេទ';
        $th5 = 'អាយុ';
        $th6 = 'លេខទូរស័ព្ទ';
        $th7 = 'អាស័យដ្ឋាន';
        $th8 = 'ទន្តបណ្ឌិត';
        $th9 = 'ដំណើរការ';
        $th10 = 'អ៊ីម៉ែល';
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
                <div class="py-2">
                  <?php 
                  // if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                    <button type="button" id="create" class="btn btn-primary d-none">
                      <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                      <i class="bx bx bx-plus d-block d-sm-none"></i>
                    </button>
                    <?php 
                  // }
                   ?>
                </div>
              </div>
              <!-- Table -->
              <div class="card">
                <div class="card-body py-3 px-0">
                  <div class="table-responsive-md py-2 px-0">
                    <table class="table pt-2 mx-auto" id="dataTable">
                      <thead class="text-nowrap table-border-bottom-0">
                        <tr>
                          <th class="text-left">ID</th>
                          <th class="text-left">Patients</th>
                          <th class="text-left">Gender</th>
                          <th class="text-left">Age</th>
                          <th class="text-left">Phone</th>
                          <th class="text-left">Dentist</th>
                          <th class="text-left">Status</th>
                          <th class="text-left">Action</th>
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                    </table>
                  </div>
                </div>
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
              <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 70%;" tabindex="-1" id="patientOffcanvas" 
                aria-labelledby="patientOffcanvasLabel"> 
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="patientOffcanvasLabel"><i class="bx bx-user-plus me-2"></i> New Register Patient</h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
				        <div class="offcanvas-body">
                  <form  id="addForm" method="POST" enctype="multipart/form-data"> 

                    <?php echo inpImage('image', '../images/profiles/0.jpg'); ?>
                    <div class="row mb-3">
                      <div class="col">
                        <label for="id" class="form-label"><?php echo $m1; ?></label>
                        <div class="input-group input-group-merge">
                          <input type="text" class="form-control" id="id" name="id" />
                          <button type="button" class="input-group-text cursor-pointer" id="editPatientID" value='0'><i id='iconPatientID'></i></button>
                        </div>   
                      </div>
                      <?php //echo inpText($m1, 'id', '','1'); ?>
                      <?php echo inpText($m2, 'code', '','2'); ?>
                      <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_description; ?></div>
                    </div>
                    <div class="row mb-3"><?php echo selectData($m3, 'membership', 'SELECT * FROM tbl_membership ORDER BY memb_discount ASC', 'memb_type'); ?></div>
                    <div class="row mb-3"><?php echo inpText($m4, 'name', 'Ex: Sun Kimhuon'); ?></div>
                    <div class="row mb-3"> 
                      <?php echo selectGender($m5, 'gender'); ?>
                      <?php echo inpDate($m6, 'dob', ''); ?>
                      <?php echo inpNum($m12, 'age', ''); ?>
                    </div>
                    <div class="row mb-3">
                      <?php echo inpText($m7, 'contact', 'Ex: 012 345 678', 0); ?>
                      <?php echo inpText($m11, 'email', 'Ex: me@mail.com', 0); ?>
                    </div>
                    <div class="row mb-3"><?php echo inpText($m8, 'address', 'Ex: Toul Kork, Phnom Penh', 0); ?></div>
                    <div class="row mb-3"><?php echo selectData($m9, 'dentist', 'SELECT * FROM `tbl_staff` WHERE `id` > 1 AND `staff_status` = 1 ORDER BY `id` ASC', 'staff_fname'); ?></div>
                    <div class="row mb-3"><?php echo inpDateTime($m10, 'datetime'); ?></div> 

                    <div class="offcanvas-footer" style="margin-bottom:50px;margin-top:50px;">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form> 
                </div>
              </div> 
               <!-- </div>
              <div class="position-fixed progress w-100 top-0 left-0">
                <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
              </div>
            </div> -->
            <?php include_once('../inc/footage.php'); ?>
          </div>
        </div>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?>
    <script src="../script/page_main.js"></script>
    
    <script src="../assets/vendor/libs/dropzone/dropzone.js"></script>  
    <script src="../script/page_patient_list.js"></script> 

    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Patients");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>
