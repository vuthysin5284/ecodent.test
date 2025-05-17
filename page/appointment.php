<?php
  $page = 'Appointment';
  session_start();
  $log = $_SESSION['uid'];
  $lang = 1;
  // include_once('../inc/session.php');
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
      $page_category = 'Appointments';
      $page_title = 'Appointment';
      $text_btn_create = 'New';
      $text_form = 'Appointment Information';
      $text_form_description = '* Patient\'s ID, Image, and Qr Code is auto-fill when you select a patient!';
      $m1 = 'Patient ID';
      $m2 = 'Qr Code';
      $m3 = 'Full Name';
      $m4 = 'Date & Time';
      $m5 = 'Duration';
      $m6 = 'Dentist';
      $m7 = 'Remark';
      $th2 = 'Image';
      $th3 = 'Patient Info';
      $th4 = 'Date & Time';
      $th5 = 'Duration';
      $th6 = 'Dentist';
      $th7 = 'Remark';
      $th8 = 'Created By';
      $th9 = 'Action';
    } else {
      $page_category = 'ការរំលឹក';
      $page_title = 'ការណាត់ជួប';
      $text_btn_create = 'បង្កើតថ្មី';
      $text_form = 'បញ្ចូលការណាត់ជួប';
      $text_form_description = '* លេខកូដ រូបភាព និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
      $m1 = 'លេខកូដ';
      $m2 = 'Qr Code';
      $m3 = 'ឈ្មោះអតិថិជន';
      $m4 = 'ពេលវេលា';
      $m5 = 'រយៈពេល';
      $m6 = 'ទន្តបណ្ឌិត';
      $m7 = 'សំគាល់';
      $th2 = 'រូបភាព';
      $th3 = 'ពត៌មានអតិថិជន';
      $th4 = 'ពេលវេលា';
      $th5 = 'រយៈពេល';
      $th6 = 'ទន្តបណ្ឌិត';
      $th7 = 'សំគាល់';
      $th8 = 'បង្កើតដោយ';
      $th9 = 'ដំណើរការ';
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
              <?php //include_once('../inc/notification_menu.php'); ?>
              <div class="d-flex justify-content-between">
                <div class="col-sm-9">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?>
                  </h4>
                </div>
                <?php if ($nav_staff_position_id == 1 || $nav_staff_position_id == 2) { ?>
                <div class="col-sm-3">
                  <div class="row">
                    <div class="d-flex h-100 col-lg-12 col-md-12 col-sm-12 col-12 mb-4">
                      <div class="input-group input-group-merge" id="reportrange">
                        <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                        <input type="text" id="date" class="form-control bg-white text-center" readonly />
                      </div>
                      <input type="hidden" id="str" class="form-control bg-white text-center" readonly />
                      <button type="button" class="btn btn-icon btn-primary mx-2" id="btnFilter"><i class="bx bx-filter-alt"></i></button>
                    </div>
                  </div>
                  <?php 
                // if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                  <!-- <a href="calendar.php" class="btn btn-label-primary">
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx-calendar"></i>&nbsp; Calendar View</span>
                    <i class="bx bx-calendar d-block d-sm-none"></i>
                  </a>-->
                  <button type="button" id="create" class="btn btn-primary d-none">
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; < ?php echo $text_btn_create; ?></span>
                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                  </button> 
                <?php 
              // }
               ?>
                </div>
                <?php } ?>
              </div>
              <!-- <div class="row">
                <div class="d-flex h-100 col-lg-3 col-md-5 col-sm-12 col-12 mb-4">
                  <div class="input-group input-group-merge" id="reportrange">
                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                    <input type="text" id="date" class="form-control bg-white text-center" readonly />
                  </div>
                  <input type="hidden" id="str" class="form-control bg-white text-center" readonly />
                  <button type="button" class="btn btn-icon btn-primary mx-2" id="btnFilter"><i class="bx bx-filter-alt"></i></button>
                </div>
              </div> -->
              <!-- <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle">< ?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> -->
                      
                <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 80%;" tabindex="-1" id="appointmentOffcanvas" 
                  aria-labelledby="appointmentOffcanvasLabel"> 
                  <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="appointmentOffcanvasLabel"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <form  id="addForm" method="POST" enctype="multipart/form-data"> 
                      <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                        <img src="../images/profiles/0.jpg" alt="img" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                      </div>
                      <div class="row mb-3">
                        <?php echo inpHidden('id', '','2'); ?>
                        <?php echo inpText($m1, 'cid', '','2'); ?>
                        <?php echo inpText($m2, 'code', '','2'); ?>
                          <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_description; ?></div>
                        </div>
                      <div class="mb-3 row">
                        <div class="col">
                          <label for="name" class="form-label"><?php echo $m3; ?></label>
                          <select class="form-select" id="name" name="name"></select>
                          <input type="text" class="form-control" id="showName" readonly />
                        </div>
                      </div>
                      <div class="row mb-3"><?php echo inpDateTime($m4, 'datetime'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m5, 'duration', 'SELECT * FROM `tbl_treatment_duration` ORDER BY `id` ASC', 'trmt_duration'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m6, 'sid', 'SELECT * FROM `tbl_staff` WHERE `id` > 1 AND `staff_status` = 1 ORDER BY `id` ASC', 'staff_fname'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m7, 'note', 'Ex: Orthodontics', 3); ?></div>
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
                </div>
              </div> -->
              <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center"><?php echo $th2; ?></th>
                          <th class="text-left"><?php echo $th3; ?></th>
                          <th class="text-center"><?php echo $th4; ?></th>
                          <th class="text-center"><?php echo $th5; ?></th>
                          <th class="text-center"><?php echo $th6; ?></th>
                          <th class="text-center"><?php echo $th7; ?></th>
                          <th class="text-center"><?php echo $th8; ?></th>
                          <th class="text-center"><?php echo $th9; ?></th>
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
    <script src="../script/page_notification_appointment.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        // var activeMenu = document.getElementById("Appointments");
        // activeMenu.classList.add("active");
        // activeMenu.classList.add("open");
        var activeSubMenu = document.getElementById("Appointments");
        activeSubMenu.classList.add("active");
        // var activeSubMenu = document.getElementById("notification-appointment");
        // activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>