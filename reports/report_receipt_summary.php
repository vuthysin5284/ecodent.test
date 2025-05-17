<?php
  $page = 'Receipt Report';
  // include_once('../inc/session.php');
  session_start();
  $log = $_SESSION['uid'];
  $clinic_id = $_SESSION['business_id'];
  $lang = 1;
  include_once ('../inc/config.php');
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
        $page_category = 'Report';
        $page_title = 'Receipt Payment Report';
        $text_dropdown = 'Show More';
        $graph_title = 'Receipt Report';
        $th2 = 'Receipt Description';
        $th3 = 'Category';
        $th4 = 'Supplier';
        $th5 = 'Total';
        $th6 = 'Remain';
        $th7 = 'Created By';
      } else {
        $page_category = 'របាយការណ៍';
        $page_title = 'ចំណាយ';
        $text_dropdown = 'ពត៌មានបន្ថែម';
        $graph_title = 'របាយការណ៍ចំណាយ';
        $th2 = 'ការចំណាយ';
        $th3 = 'ប្រភេទ';
        $th4 = 'អ្នកផ្គត់ផ្គង់';
        $th5 = 'ទឹកប្រាក់សរុប';
        $th6 = 'ទឹកប្រាក់នៅខ្វះ';
        $th7 = 'បង្កើតដោយ';
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
                <div class="me-auto col-lg-7 col-md-7 col-sm-12 col-12">
                  <h4 class="fw-bold py-2 mb-2">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div>
                <div class="d-flex h-100 col-lg-5 col-md-5 col-sm-12 col-12 mb-4">
                  <label for="reportrange" class="form-label col-3" style="margin:auto">Entry date: </label>
                  <div class="input-group input-group-merge" id="reportrange">
                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                    <input type="text" id="date" class="form-control bg-white text-center" readonly />
                  </div>
                  <input type="hidden" id="str" class="form-control bg-white text-center" readonly />
                  <button type="button" class="btn btn-icon btn-primary mx-2" id="btnFilter"><i class="bx bx-filter-alt"></i></button>
                </div>
              </div>

              
              <div class="card">
                <?php include_once('../inc/report_receipt_menu.php'); ?> 
                <div class="card-body">
                  <div class="table-responsive text-nowrap p-4 me-md-2 me-sm-4 me-4">
                    <div class="row">
                      <table class="table table-bordered" id="dataTable">
                        <thead>
                          <tr>
                            <th class="text-center"></th> 
                            <th class="text-center">#</th> 
                            <th class="text-center">Entry date</th>
                            <th class="text-center">Post date</th>
                            <th class="text-center">Received by</th>
                            <th class="text-center">Amount</th>
                            <th class="text-center">Count</th>
                            <th class="text-left w-50">Remark</th>
                          </tr>
                        </thead>
                        <tbody class="text-nowrap"></tbody>
                      </table>
                    </div>
                  </div>
                </div
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
    <script src="../script/page_report_receipt.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Report");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("Receipt Payment Report");
        activeSubMenu.classList.add("active");
        
        var activeTabMenu = document.getElementById("report-receipt-summary");
        activeTabMenu.classList.add("active");
 
      };
    </script>
  </foot>
</html> 