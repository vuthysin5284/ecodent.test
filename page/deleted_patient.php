<?php
  $page = 'Deleted Patient';
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
        $page_category = 'Deleted History';
        $page_title = 'Patient';
        $th1 = 'ID Code';
        $th2 = 'Image';
        $th3 = 'Patient';
        $th4 = 'Gender';
        $th5 = 'Age';
        $th6 = 'Contact';
        $th7 = 'Address';
        $th8 = 'Action';
      } else {
        $page_category = 'ប្រវត្តិការលុប';
        $page_title = 'អតិថិជន';
        $th1 = 'លេខកូដ';
        $th2 = 'រូបភាព';
        $th3 = 'អតិថិជន';
        $th4 = 'ភេទ';
        $th5 = 'អាយុ';
        $th6 = 'លេខទូរស័ព្ទ';
        $th7 = 'អាស័យដ្ឋាន';
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
              <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
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
                          <th class="text-center"><?php echo $th3; ?></th>
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
    <script src="../script/page_deleted_patient.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Deleted History");
        activeMenu.classList.add("active");
        activeMenu.classList.add("open");
        var activeSubMenu = document.getElementById("Deleted Patient");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>
