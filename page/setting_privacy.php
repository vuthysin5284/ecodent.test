<?php
	session_start();
	$page = 'Privacy';
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
        $page_category = 'Setting';
        $page_title = 'Privacy';
        $m1 = 'Username';
        $m2 = 'Old Password';
        $m3 = 'New Password';
        $m4 = 'Confirm Password';
      } else {
        $page_category = 'ការកំណត់';
        $page_title = 'ពាក្យសម្ងាត់';
        $m1 = 'ឈ្មោះគណនី';
        $m2 = 'លេខសម្ងាត់ចាស់';
        $m3 = 'លេខសម្ងាត់ថ្មី';
        $m4 = 'បញ្ជាក់លេខសម្ងាត់';
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
              <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div>
              </div>
              <div class="row">
                <div class="col-md-6 col-12 me-auto">
                  <form id="addForm" method="POST" enctype="multipart/form-data">
                    <div class="card">
                      <div class="card-body p-3">
                        <div class="row mb-3"><?php echo inpText($m1, 'username', 'Username'); ?></div>
                        <div class="row mb-3"><?php echo inpPwd($m2, 'oldpwd'); ?></div>
                        <div class="row mb-3"><?php echo inpPwd($m3, 'newpwd'); ?></div>
                        <div class="row mb-3"><?php echo inpPwd($m4, 'confirmpwd'); ?></div>
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                    </div>
                  </form>
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
    <script src="../script/page_privacy.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Setting");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("Membership");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>
