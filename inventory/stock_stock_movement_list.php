<?php
  $page = 'Product List';
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
        $page_title = 'Stock Movement List';
        $text_btn_create = 'Make Request';
        $text_form = 'Staff Information';
        $text_form_description = '* Product\'s ID, and Qr Code is auto generated!';
        $m1 = 'Product ID';
        $m2 = 'Qr Code';
        $m3 = 'Product Description';
        $m4 = 'Category';
        $m5 = 'Supplier';
        $m6 = 'Qty';
        $m7 = 'Price';
        $m8 = 'Minimum';
        $th2 = 'Image';
        $th3 = 'Product Info';
        $th4 = 'Qty';
        $th5 = 'Price';
        $th6 = 'Total';
        $th7 = 'Minimum';
        $th8 = 'Created By';
        $th9 = 'Action';
      } else {
        $page_category = 'ឃ្លាំងស្តុក';
        $page_title = 'សម្ភារៈ';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'បញ្ចូលពត៌មានបុគ្គលិក';
        $text_form_description = '* លេខកូដ និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
        $m1 = 'លេខកូដ';
        $m2 = 'Qr Code';
        $m3 = 'ពត៌មានសម្ភារៈ';
        $m4 = 'ប្រភេទ';
        $m5 = 'អ្នកផ្គត់ផ្គង់';
        $m6 = 'ចំនួន';
        $m7 = 'តម្លៃ ($)';
        $m8 = 'អប្បបរមា';
        $th2 = 'រូបភាព';
        $th3 = 'ពត៌មានសម្ភារៈ';
        $th4 = 'ចំនួន';
        $th5 = 'តម្លៃ ($)';
        $th6 = 'សរុប ($)';
        $th7 = 'អប្បបរមា';
        $th8 = 'បង្កើតដោយ';
        $th9 = 'ដំណើរការ';
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
              <!-- action -->
              <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div>
                <div class="py-2">
                <!--?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?-->
                  <button type="button" id="create" class="btn btn-primary">
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                  </button>
                  <!--?php }?-->
                </div>
              </div> 
              <!-- modal form -->
              <!-- <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle">< ?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> -->
                <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 800px;" tabindex="-1" id="stockmovementOffcanvas" 
                  aria-labelledby="stockmovementOffcanvasLabel"> 
                  <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="stockmovementOffcanvasLabel"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                  <form  id="addForm" method="POST" enctype="multipart/form-data"> 
                      <?php echo inpImage('image', '../images/profiles/0.jpg'); ?>
                      <div class="row mb-3"> 
                        <?php echo inpText($m1, 'id', '','2'); ?>
                        <?php echo inpText($m2, 'code', '','2'); ?>
                        <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_description; ?></div>
                      </div>
                      <div class="row mb-3"><?php echo inpText($m3, 'description', 'Ex: Impression Silicon'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m4, 'categ', 'SELECT * FROM `tbl_product_category` where type_name="Product" ORDER BY `prod_category` ASC', 'prod_category'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m5, 'suppid', 'SELECT * FROM `tbl_supplier` WHERE `exp_cate_id` = 4 ORDER BY `id` ASC', 'supp_fname'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m6, 'qty', 'Ex: 15'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m7, 'cost', 'Ex: 75.00'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m8, 'min', 'Ex: 3'); ?></div>
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
                </div> -->
                <div class="position-fixed progress w-100 top-0 left-0">
                  <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
                </div>
              </div>
              <!-- datatable list -->
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
                          <th class="text-center"><?php echo $th7; ?></th>
                          <th class="text-center"><?php echo $th8; ?></th>
                          <th class="text-center"><?php echo $th9; ?></th>
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
    <script src="script/page_stockmovement_list.js"></script>
    <script type="text/javascript">
      window.onload = function(){
          var activeMenu = document.getElementById("Inventory");
          activeMenu.classList.add("open");
          activeMenu.classList.add("active");
          var activeSubMenu = document.getElementById("sub-Stock Movement");
          activeSubMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>
