<?php
  $page = "Follow Up";
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
        $page_title = 'Follow Up';
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
        $page_title = 'ការតាមដាន';
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
              <div class="row justify-content-between">
                <div class="me-auto col-lg-9 col-md-7 col-sm-12 col-12">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div>
                <div class="d-flex h-100 col-lg-3 col-md-5 col-sm-12 col-12 mb-4">
                  <div class="input-group input-group-merge" id="reportrange">
                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                    <input type="text" id="date" class="form-control bg-white text-center" readonly />
                  </div>
                  <input type="hidden" id="str" class="form-control bg-white text-center" readonly />
                  <button type="button" class="btn btn-icon btn-primary ms-2" id="btnFilter"><i class="bx bx-filter-alt"></i></button>
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
                          <input type="text" class="form-control" id="name" readonly />
                        </div>
                      </div>
                      <div class="row mb-3"><?php echo inpDateTime($m4, 'datetime'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m5, 'duration', 'SELECT * FROM `tbl_treatment_duration` ORDER BY `id` ASC', 'trmt_duration'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m6, 'sid', 'SELECT * FROM `tbl_staff` WHERE `id` > 1 AND `staff_status` = 1 ORDER BY `id` ASC', 'staff_fname'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m7, 'note', 'Ex: Orthodontics', 3); ?></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form>
                </div>
              </div>
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
    <?php include_once('../inc/footer.php'); ?> 
    <script src="../script/page_main.js"></script>
    <script src="../script/page_notification_followup.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Appointments");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("Follow Up");
        activeSubMenu.classList.add("active");
      };
    </script>
  </body>
</html>