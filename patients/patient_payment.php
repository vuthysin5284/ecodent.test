<?php
  $page = 'Completed Invoice';
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
        $page_category = 'Revenues';
        $page_title = 'Receipt Payment';
        $th2 = 'Image';
        $th3 = 'Patient Info';
        $th4 = 'Invoice No';
        $th5 = 'Dentist';
        $th6 = 'Status';
        $th7 = 'Created By';
        $th8 = 'Action';
      } else {
        $page_category = 'វិក្កយបត្រ';
        $page_title = 'បង់ប្រាក់រួចរាល់';
        $th2 = 'រូបភាព';
        $th3 = 'ពត៌មានអតិថិជន';
        $th4 = 'វិក្កយបត្រ';
        $th5 = 'ទន្តបណ្ឌិត';
        $th6 = 'សំគាល់';
        $th7 = 'បង្កើតដោយ';
        $th8 = 'ដំណើរការ';
      }
      include_once('../inc/header.php');
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
              <?php include_once('../inc/patient_menu.php'); ?>

              <div class="row justify-content-between">
                <div class="me-auto col-lg-9 col-md-7 col-sm-12 col-12">
                  <h4 class="fw-bold py-2 mb-3">
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
              
              
              <div class="card">
                <div class="table-responsive text-nowrap p-4 me-md-2 me-sm-4 me-4">
                  <div class="row">
                    <table class="table table-bordered" id="dataTable">
                      <thead>
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center"><?php echo $th2; ?></th>
                          <th class="text-left"><?php echo $th3; ?></th>
                          <th class="text-center"><?php echo $th4; ?></th>
                          <th class="text-center"><?php echo $th5; ?></th>
                          <th class="text-center"><?php echo $th6; ?></th>
                          <th class="text-center"><?php echo $th7; ?></th>
                          <th class="text-center"><?php echo $th8; ?></th>
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
    <script src="../script/page_invoice_completed.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Patients");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active"); 
        var activeSubMenu = document.getElementById("patient-payment");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>