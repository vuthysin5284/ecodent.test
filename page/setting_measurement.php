<?php
  $page = 'Measurement';
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
        $page_category = 'Setting';
        $page_title = 'Measurement';
        $text_btn_create = 'New';
        $text_form = 'Measurement Category';
        $m1 = 'Category ID';
        $m2 = 'Medicine Category';
        $m3 = 'Measurement';
        $th2 = 'Medicine Category';
        $th3 = 'Measurement';
        $th4 = 'Action';
      } else {
        $page_category = 'ការកំណត់';
        $page_title = 'ប្រភេទរង្វាស់';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'ប្រភេទរង្វាស់';
        $m1 = 'លេខកូដ';
        $m2 = 'ប្រភេទឱសថ';
        $m3 = 'ប្រភេទរង្វាស់';
        $th2 = 'ប្រភេទឱសថ';
        $th3 = 'ប្រភេទរង្វាស់';
        $th4 = 'ដំណើរការ';
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
                <!--?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?-->
                  <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
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
                      
                <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 500px;" tabindex="-1" id="measurenmentOffcanvas" 
                  aria-labelledby="measurenmentOffcanvasLabel"> 
                  <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="measurenmentOffcanvasLabel"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                  <form  id="addForm" method="POST" enctype="multipart/form-data"> 
                      <div class="row mb-3"> 
                        <?php echo inpText($m1, 'id', '','2'); ?>
                      </div>
                      <div class="row mb-3">
                        <?php echo inpText($m2, 'measure_eng', 'Ex: Capsule'); ?>
                      </div>
                      <div class="row mb-3">
                        <?php echo inpText($m3, 'measure_kh', 'Ex: គ្រាប់'); ?>
                      </div>
                      
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
              <!-- Table -->
              <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center w-50"><?php echo $th2; ?></th>
                          <th class="text-center w-50"><?php echo $th3; ?></th>
                          <th class="text-center"><?php echo $th4; ?></th>
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
    <script src="../script/page_measurement.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Setting");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-Measurement");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>
