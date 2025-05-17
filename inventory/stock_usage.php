<?php
  $page = 'Supplier List';
  // include_once('../inc/session.php');
  session_start();
  $log = $_SESSION['uid'];
  $clinic_id = $_SESSION['business_id'];
  $lang = 1;;
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
        $page_category = 'Inventory';
        $page_title = 'Supplier';
        $text_btn_create = 'New';
        $text_form = 'Supplier Information';
        $text_form_description = '* Supplier\'s ID, and Qr Code is auto generated!';
        $m1 = 'Supplier ID';
        $m2 = 'Qr Code';
        $m3 = 'Supplier Name';
        $m4 = 'Category';
        $m5 = 'Contact';
        $m6 = 'Address';
        $th2 = 'Image';
        $th3 = 'Supplier Info';
        $th4 = 'Category';
        $th5 = 'Created By';
        $th6 = 'Action';
      } else {
        $page_category = 'ឃ្លាំងស្តុក';
        $page_title = 'អ្នកផ្គត់ផ្គង់';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'បញ្ចូលពត៌មានអ្នកផ្គត់ផ្គង់';
        $text_form_description = '* លេខកូដ និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
        $m1 = 'លេខកូដ';
        $m2 = 'Qr Code';
        $m3 = 'អ្នកផ្គត់ផ្គង់';
        $m4 = 'ប្រភេទ';
        $m5 = 'លេខទូរស័ព្ទ';
        $m6 = 'អាស័យដ្ឋាន';
        $th2 = 'រូបភាព';
        $th3 = 'ពត៌មានអ្នកផ្គត់ផ្គង់';
        $th4 = 'ប្រភេទ';
        $th5 = 'បង្កើតដោយ';
        $th6 = 'ដំណើរការ';
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
                <div class="p-2">
                <?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                  <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                  </button>
                  <?php }?>
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
                      <?php echo inpImage('image', '../images/profiles/0.jpg'); ?>
                      <div class="row mb-3"> 
                        <?php echo inpText($m1, 'id', '','2'); ?>
                        <?php echo inpText($m2, 'code', '','2'); ?>
                        <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_description; ?></div>
                      </div>
                      <div class="row mb-3"><?php echo inpText($m3, 'name', 'Ex: Dentyst Solution'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m4, 'category', 'SELECT * FROM tbl_product_category WHERE `id` > 1 and type_name in("Product","Service") ORDER BY `prod_category` ASC', 'prod_category'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m5, 'contact', 'Ex: 012 345 678'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m6, 'address', 'Ex: Toul Kork, Phnom Penh'); ?></div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form>
                </div>
                <div class="position-fixed progress w-100 top-0 left-0">
                  <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
              </div>
              <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center"><?php echo $th2; ?></th>
                          <th class="text-left"><?php echo $th3; ?></th>
                          <th class="text-center"><?php echo $th4; ?></th>
                          <th class="text-center"><?php echo $th5; ?></th>
                          <th class="text-center"><?php echo $th6; ?></th>
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
    <script src="../script/page_supplier_list.js"></script>
    <script type="text/javascript">
      window.onload = function(){
          var activeMenu = document.getElementById("Inventory");
          activeMenu.classList.add("open");
          activeMenu.classList.add("active");
          var activeSubMenu = document.getElementById("sub-Stock Usage");
          activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>
