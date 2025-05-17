<?php
  $page = 'Expense List';
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
        $page_category = 'Expense';
        $page_title = 'Expense List';
        $text_btn_create = 'New Payment';
        $text_form = 'Expense Information';
        $text_form_body = '* Expense\'s ID, and Qr Code is auto generated!';
        $m1 = 'Expense ID';
        $m2 = 'Qr Code';
        $m3 = 'Expense Description';
        $m4 = 'Category';
        $m5 = 'Supplier';
        $m6 = 'Total Amount ($)';
        $th2 = 'Expense Description';
        $th3 = 'Category';
        $th4 = 'Supplier';
        $th5 = 'Total ($)';
        $th6 = 'Remain ($)';
        $th7 = 'Status';
        $th8 = 'Action';
      } else {
        $page_category = 'ការចំណាយ';
        $page_title = 'បញ្ជីការចំណាយ';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'ការចំណាយ';
        $text_form_body = '* លេខកូដ និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
        $m1 = 'លេខកូដ';
        $m2 = 'Qr Code';
        $m3 = 'ការចំណាយ';
        $m4 = 'ប្រភេទ';
        $m5 = 'អ្នកផ្គត់ផ្គង់';
        $m6 = 'ទឹកប្រាក់សរុប ($)';
        $th2 = 'ការចំណាយ';
        $th3 = 'ប្រភេទ';
        $th4 = 'អ្នកផ្គត់ផ្គង់';
        $th5 = 'ទឹកប្រាក់សរុប ($)';
        $th6 = 'នៅសល់ ($)';
        $th7 = 'សំគាល់';
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
            <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div>
                <div class="py-2">
                <!-- < ?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?> -->
                  <button type="button" id="create" class="btn btn-primary">
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                  </button>
                  <!-- < ?php }?> -->
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
              <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 500px;" tabindex="-1" id="expenseOffcanvas" 
                aria-labelledby="expenseOffcanvasLabel"> 
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="expenseOffcanvasLabel"><?php echo $text_form; ?></h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
				        <div class="offcanvas-body">
                  <form  id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="row mb-3"> 
                      <?php echo inpText($m1, 'id', '','2'); ?>
                      <?php echo inpText($m2, 'code', '','2'); ?>
                      <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_body; ?></div>
                    </div>
                    <div class="row mb-3"><?php echo inpText($m3, 'description', 'Ex: Electricity'); ?></div>
                    <div class="row mb-3"><?php echo selectData($m4, 'categ', 'SELECT * FROM tbl_product_category where type_name in("Product","Service") ORDER BY `prod_category` ASC', 'prod_category'); ?></div>
                    <div class="row mb-3">
                      <div class="col">
                        <label for="supplier" class="form-label"><?php echo $m5;?></label>
                        <select class="form-select" id="supplier" name="supplier" required >
                          <option hidden value="">--- select item ---</option>
                        </select>
                      </div>
                    </div>
                    <div class="row mb-3"><?php echo inpText($m6, 'amount', 'Ex: 75'); ?></div>
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
              <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-left"><?php echo $th2; ?></th>
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
    <script src="../script/page_expense_list.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Expenses");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("Make Payment");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>
