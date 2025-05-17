<?php
  $page = 'Services';
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
        $page_category = 'Inventory';
        $page_title = 'Service List';
        $text_btn_create = 'New';
        $text_form = 'Treatment Service';
        $m1 = 'Service ID';
        $m2 = 'Treatment Service';
        $m3 = 'Category';
        $m4 = 'Service Price ($)';
        $m5 = 'Cost Deduction (%)';
        $th2 = 'Category';
        $th3 = 'Treatment Service';
        $th4 = 'Price ($)';
        $th5 = 'Cost (%)';
        $th6 = 'Action';
      } else {
        $page_category = 'ការកំណត់';
        $page_title = 'សេវាកម្មព្យាបាល';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'សេវាកម្មព្យាបាល';
        $m1 = 'លេខកូដ';
        $m2 = 'សេវាកម្មព្យាបាល';
        $m3 = 'ប្រភេទ';
        $m4 = 'តម្លៃសេវាកម្ម ($)';
        $m5 = 'កាត់ថ្លៃចំណាយ (%)';
        $th2 = 'ប្រភេទ';
        $th3 = 'សេវាកម្មព្យាបាល';
        $th4 = 'តម្លៃសេវាកម្ម ($)';
        $th5 = 'កាត់ថ្លៃចំណាយ (%)';
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
                <div class="py-2">
                <!--?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?-->
                  <button type="button" id="create" class="btn btn-primary" >
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                  </button>
                  <!--?php }?-->
                </div>
              </div>
              <!-- <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle">< ?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> -->
                <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 500px;" tabindex="-1" id="serviceOffcanvas" 
                  aria-labelledby="serviceOffcanvasLabel"> 
                  <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="serviceOffcanvasLabel"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                  <form  id="addForm" method="POST" enctype="multipart/form-data"> 
                      <div class="row mb-3"><?php echo inpText($m1, 'id', '','2'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m2, 'service', 'Ex: Composite Filling'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m3, 'category', "SELECT * FROM `tbl_product_category` where type_name='Product'  ORDER BY `prod_category` ASC", 'prod_category'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m4, 'price', 'Ex: 25'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m5, 'cost', 'Ex: 10'); ?></div>
                     
                      <div class="offcanvas-footer" style="margin-bottom:50px;margin-top:50px;">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form> 
                </div>
              </div> 
                    <!-- <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form>
                </div>
              </div> -->
              <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center"><?php echo $th2; ?></th>
                          <th class="text-center w-100"><?php echo $th3; ?></th>
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
    <script src="../script/page_treatment_service.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Inventory");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-Treatment Service");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>
