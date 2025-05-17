<?php
  $page = 'Completed Invoice';
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
        $page_category = 'Revenues';
        $page_title = 'Depreciation Payment';
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
              <?php //include_once('../inc/invoice_menu.php'); ?>
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
              <!-- btn -->
              <!-- <div class="d-flex justify-content-between">
                <div class="me-auto"> </div>
                <div class="py-2"> 
                  < ?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                    <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                      <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-money"></i>&nbsp; Cash Transfer</span>
                      <i class="bx bx bx-plus d-block d-sm-none"></i>
                    </button>
                  < ?php } ?> 
                </div>
              </div> -->
              <!-- form -->
              <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog" style="max-width: 99%;" >
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle">Form receive payment</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> 
                      <div class="mb-3 row">
                        <div class="col-sm-3">   
                          <div class="col-12"> 
                              <?php echo inpDate("Entry date", 'entry_date', ''); ?>
                              <?php echo inpDate("Post date", 'post_date', ''); ?> 
                              <?php echo inpTextarea("Remark", 'remark', ''); ?>  
                          </div>

                        </div>
                        <div class="col-sm-9"> 
                          <label for="dataPaymentListTable" class="form-label">Payment List</label>
                          <div class='table-responsive'>
                            <table class="table table-bordered" id="dataPaymentListTable" style="width: 100%;" >
                              <thead>
                                <tr>
                                  <th class="text-center">Check</th> 
                                  <th class="text-center">Invoice No</th>
                                  <th class="text-left">Patient</th>
                                  <th class="text-left">Dentist</th>
                                  <th class="text-center">Amount ($)</th>
                                  <th class="text-center">Method</th>
                                  <th class="text-left">Created By</th>
                                  <th class="text-center">Date</th>
                                  <th class="text-center">Payment ID</th>
                                </tr>
                              </thead>
                              <tbody class="text-nowrap"></tbody>
                            </table>
                          </div>

                        </div>
                      </div> 
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary" id="btnSubmitReceivedPayment">Submit Receive Payment</button>
                    </div>
                  </form>
                </div>
              </div>

              <!-- list -->
              <div class="card">
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
    <script src="../script/page_cash_transaction.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Revenue");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-Depreciation Payment");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>