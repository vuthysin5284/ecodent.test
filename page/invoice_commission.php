<?php
  $page = 'Share Commission';
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
        $page_category = 'Staff';
        $page_title = 'Staff Benefits';
        $th1 = 'Invoice No';
        $th2 = 'Image';
        $th3 = 'Patient';
        $th4 = 'Invoice';
        $th5 = 'Total ($)';
        $th6 = 'Share ($)';
        $th7 = 'Status';
        $th8 = 'Dentist';
        $th9 = 'Action';
      } else {
        $page_category = 'បុគ្គលិក';
        $page_title = 'បែងចែកភាគលាភ';
        $th1 = 'លេខវិក្កយបត្រ';
        $th2 = 'រូបភាព';
        $th3 = 'អតិថិជន';
        $th4 = 'វិក្កយបត្រ';
        $th5 = 'សរុប ($)';
        $th6 = 'ទទួលបាន ($)';
        $th7 = 'សំគាល់';
        $th8 = 'ទន្តបណ្ឌិត';
        $th9 = 'ដំណើរការ';
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
              <div class="row justify-content-between">
                <div class="me-auto col-lg-9 col-md-7 col-sm-12 col-12">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div>
                <div class="d-flex h-100 col-lg-3 col-md-5 col-sm-12 col-12 mb-4">
                  <div class="input-group input-group-merge ms-2" id="reportrange">
                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                    <input type="text" id="date" class="form-control bg-white text-center" readonly />
                  </div>
                  <input type="hidden" id="str" class="form-control bg-white text-center" readonly />
                  <button type="button" class="btn btn-icon btn-primary ms-2" id="btnFilter"><i class="bx bx-filter-alt"></i></button>
                </div>
              </div>
              <div class="card">
                <div class="card-header p-3">
                  <h5 class="card-title p-2 mb-2">Filter</h5>
                  <div class="row align-items-center p-2 mb-2">
                    <div class="col-xl-3 col-md-4 col-6">
                      <?php
                        $STAFF_QUERY = mysqli_query($CON, "SELECT `id`, `staff_fname` FROM `tbl_staff` WHERE `staff_status` = 1");
                        echo '<div class="input-group">';
                        echo '<select class="form-select" id="sid">';
                        echo '<option value="">Dentist</option>';
                        while ($STAFF_ROW = mysqli_fetch_assoc($STAFF_QUERY)) {
                          echo '<option value="'.$STAFF_ROW['id'].'">'.$STAFF_ROW['staff_fname'].'</option>';
                        }
                        echo '</select>';
                        echo '</div>';
                      ?>
                    </div>
                    <div class="col-xl-3 col-md-4 col-6">
                      <div class="input-group">
                        <select class="form-select" id="zid">
                          <option value="">Invoice</option>
                          <option value="1">Shared</option>
                          <option value="0">Not Shared</option>
                        </select>
                      </div>
                    </div>
                    <!-- <div class="col-xl-3 col-md-4 col-3">
                      <div class="input-group">  
                        <button type="button" id="create" class="btn btn-primary">
                          <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; New Benefits</span>
                            <i class="bx bx bx-plus d-block d-sm-none"></i>
                        </button> 
                      </div>
                    </div>
                    <div class="col-xl-3 col-md-4 col-3">
                      <div class="input-group">  
                        <button type="button" id="create" class="btn btn-primary">
                          <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Royalty</span>
                            <i class="bx bx bx-plus d-block d-sm-none"></i>
                        </button>
                      </div>
                    </div> -->
                  </div>
                </div>
                <hr class="m-0">
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
                          <td colspan="4"></td>
                          <td class="fw-bolder" id="grandTotal"></td>
                          <td class="fw-bolder" id="shareTotal"></td>
                          <td colspan="3"></td>
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
    <script src="../script/page_main.js"></script>
    <script src="../script/page_share_commission.js"></script>
    <script type="text/javascript">
      window.onload = function(){
          var activeMenu = document.getElementById("Employee");
          activeMenu.classList.add("open");
          activeMenu.classList.add("active");
          var activeSubMenu = document.getElementById("Staff Benefit");
          activeSubMenu.classList.add("active");
          // active tab 
          var activeTabMenu = document.getElementById("staff-benefit");
          activeTabMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>