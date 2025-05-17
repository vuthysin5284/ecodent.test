<?php
  $page = 'Queue';
  include_once('../inc/session.php');
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
        $page_title = 'Queue';
        $text_btn_create = 'New';
        $text_form = 'Queue Information';
        $text_form_description = '* Patient\'s ID, Image, and Qr Code is auto-fill when you select a patient!';
        $m1 = 'Patient ID';
        $m2 = 'Qr Code';
        $m3 = 'Full Name';
        $m4 = 'Dentist';
        $m5 = 'Duration';
        $m6 = 'Remark';
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
        $page_title = 'កំពុងរង់ចាំ';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'បញ្ចូលការណាត់ជួប';
        $text_form_description = '* លេខកូដ រូបភាព និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
        $m1 = 'លេខកូដ';
        $m2 = 'Qr Code';
        $m3 = 'ឈ្មោះអតិថិជន';
        $m4 = 'ទន្តបណ្ឌិត';
        $m5 = 'រយៈពេល';
        $m6 = 'សំគាល់';
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
              <?php //include_once ('../inc/notification_menu.php'); ?>
              <div class="row justify-content-between">
                  <div class="me-auto col-lg-9 col-md-7 col-sm-12 col-12">
                    <h4 class="fw-bold py-3 mb-4">
                      <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                    </h4>
                  </div>
                  <?php if ($nav_staff_position_id == 1 || $nav_staff_position_id == 2) { ?>
                  <form id="checkin" method="POST" class="d-flex h-100 col-lg-3 col-md-5 col-sm-12 col-12 mb-4">
                    <div class="input-group input-group-merge">
                      <span class="input-group-text"><i class="bx bx-qr"></i></span>
                      <input type="search" id="ccode" name="ccode" class="form-control" placeholder="" autofocus autocomplete="off" required />
                    </div>
                    <button type="submit" class="btn btn-icon btn-primary mt-auto ms-2"><i class="bx bx-search"></i></button>
                    <div id="searchCust" class="dropdown-menu mt-5 p-2"></div>
                  </form>
                  <?php } ?>
              </div>
              <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                        <img src="../images/profiles/0.jpg" alt="img" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                      </div>
                      <div class="row mb-3"> 
                        <?php echo inpHidden('id','2'); ?>
                        <?php echo inpText($m1, 'cid', '','2'); ?>
                        <?php echo inpText($m2, 'code', '','2'); ?>
                        <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_description; ?></div>
                      </div>
                      <div class="row mb-3"><?php echo inpText($m3, 'name', '','2'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m4, 'staff', 'SELECT * FROM `tbl_staff` WHERE `id` > 1 AND `staff_status` = 1 ORDER BY `id` ASC', 'staff_fname'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m5, 'duration', 'SELECT * FROM `tbl_treatment_duration`', 'trmt_duration'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m6, 'note', 'Orthodontics'); ?></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form>
                </div>
              </div>
              <!-- User Dropdown -->
              <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered" id="dataTable">
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
                      <tbody class="text-nowrap"> </tbody>
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
    <script src="../script/page_notification_queue.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Appointments");
        activeMenu.classList.add("active");
        activeMenu.classList.add("open"); 
        var activeSubMenu = document.getElementById("Queues List");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>