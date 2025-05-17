<?php
  $page = 'Transaction Report'; 
  session_start();
	$log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
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
        $page_title = 'Transaction';
        $text_dropdown = 'Show More';
        $th1 = 'Invoice No';
        $th2 = 'Image';
        $th3 = 'Patient';
        $th4 = 'Invoice';
        $th5 = 'Method';
        $th6 = 'Payment ($)';
        $th7 = 'Payment (៛)';
        $th8 = 'Change (៛)';
        $th9 = 'Timestamp';
        $th10 = 'Grand Total';
        
      } else {
        $page_category = 'របាយការណ៍';
        $page_title = 'សាច់ប្រាក់';
        $text_dropdown = 'ពត៌មានបន្ថែម';
        $th1 = 'លេខវិក្កយបត្រ';
        $th2 = 'រូបភាព';
        $th3 = 'អតិថិជន';
        $th4 = 'វិក្កយបត្រ';
        $th5 = 'តាមរយៈ';
        $th6 = 'ទឹកប្រាក់ ($)';
        $th7 = 'ទឹកប្រាក់ (៛)';
        $th8 = 'ប្រាក់អាប់ (៛)';
        $th9 = 'កាលបរិច្ឆេទ';
        $th10 = 'សរុប';
      }
      include_once('../inc/config.php');
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
                  <h4 class="fw-bold py-2 mb-2">
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
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center"><?php echo $th1; ?></th>
                          <th class="text-center"><?php echo $th2; ?></th>
                          <th class="text-left"><?php echo $th3; ?></th>
                          <th class="text-left"><?php echo $th4; ?></th>
                          <th class="text-center"><?php echo $th5; ?></th>
                          <th class="text-center"><?php echo $th6; ?></th>
                          <th class="text-center"><?php echo $th7; ?></th>
                          <th class="text-center"><?php echo $th8; ?></th>
                          <th class="text-center"><?php echo $th9; ?></th>
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                      <tfoot>
                        <tr>
                          <td class="fw-bolder text-end" colspan="5"><?php echo $th10?></td>
                          <td class="fw-bolder" id="usdTotal"></td>
                          <td class="fw-bolder" id="khrTotal"></td>
                          <td class="fw-bolder" id="changeTotal"></td>
                          <td></td>
                        </tr>
                      </tfoot>
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
    <script src="../script/page_report_transaction.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Analytics");
        activeMenu.classList.add("active");
      }
    </script>
  </foot>
</html>
