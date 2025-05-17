<?php
  session_start();
  $page = 'Expense Payment';
	$log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
	if (!isset($log)) { header('Location: login.php'); }
	error_reporting(0);
  $excode = $_GET['excode'];
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
        $page_title = 'Payment';
        $text_btn_create = 'New';
        $text_form = 'Expense Payment';
        $m1 = 'Amount Payment ($)';
        $m2 = 'Payment Method';
        $m3 = 'Remark';
        $m4 = 'Total Payment ($)';
        $m5 = 'Image';
        $th2 = 'Expense Description';
        $th3 = 'Category';
        $th4 = 'Supplier';
        $th5 = 'Total ($)';
        $th6 = 'Remain ($)';
        $th7 = 'Status';
        $th8 = 'Action';
        $th9 = 'Date & Time';
        $th10 = 'Remark';
        $th11 = 'Method';
        $th12 = 'Amount ($)';
        $th13 = 'Action';
        $th14= 'Total Payment :';
        $th15 = 'Remain Payment :';
      } else {
        $page_category = 'ការចំណាយ';
        $page_title = 'ទូទាត់ការចំណាយ';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'ទូទាត់ការចំណាយ';
        $m1 = 'ចំនួនទឹកប្រាក់ ($)';
        $m2 = 'តាមរយៈ';
        $m3 = 'សំគាល់';
        $m4 = 'ទឹកប្រាក់សរុប ($)';
        $m5 = 'រូបភាព';
        $th2 = 'ការចំណាយ';
        $th3 = 'ប្រភេទ';
        $th4 = 'អ្នកផ្គត់ផ្គង់';
        $th5 = 'ទឹកប្រាក់សរុប​ ($)';
        $th6 = 'ទឹកប្រាក់នៅខ្វះ ($)';
        $th7 = 'សំគាល់';
        $th8 = 'ដំណើរការ';
        $th9 = 'ពេលវេលា';
        $th10 = 'សំគាល់';
        $th11 = 'តាមរយៈ';
        $th12 = 'ចំនួនទឹកប្រាក់ ($)';
        $th13 = 'ដំណើរការ';
        $th14 = 'ទូទាត់រួចរាល់ :';
        $th15 = 'នៅសល់ :';
      }
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
    <div class="position-fixed progress w-100 top-0 left-0">
      <div class="progress-bar progress-bar-striped progress-bar-animated" id="progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="width: 0%"></div>
    </div>
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
                <?php if ($nav_staff_position_id == 1) { ?>
                <div class="py-2">
                <!-- ?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?-->
                  <button type="button" id="create" class="btn btn-primary">
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                    <i class="bx bx bx-plus d-block d-sm-none"></i>
                  </button>
                <!--?php } ?-->
                </div>
                <?php } ?>
              </div>
              <!-- <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> -->
              <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 500px;" tabindex="-1" id="expensePaymentOffcanvas" 
                aria-labelledby="expensePaymentOffcanvasLabel"> 
                <div class="offcanvas-header">
                    <h5 class="offcanvas-title" id="expensePaymentOffcanvasLabel"><?php echo $text_form; ?></h5>
                    <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                </div>
				        <div class="offcanvas-body">
                      <input type="hidden" id="excode" name="excode" value="<?php echo $excode; ?>" class="form-control" readonly/>
                      <div class="row mb-3"><?php echo inpText($m1, 'amount', 'Ex: 1000', '1'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m2, 'paym', 'SELECT * FROM `tbl_payment_method` ORDER BY `payment_method` ASC', 'payment_method'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m3, 'note', 'Ex: Filling #17', '0'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m4, 'remain', '', '2'); ?></div>
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
              <div class="card mb-4">
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
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                    </table>
                  </div>
                </div>
              </div>
              <div class="card mb-4">
                <div class="card-body p-4">
                  <form id="imgForm" method="POST" enctype="multipart/form-data" class="d-flex justify-content-between mb-4">
                    <div class="col">
                      <label for="files" class="form-label"><?php echo $m5; ?></label>
                      <input class="form-control" type="file" id="files" name="files[]" multiple required/>
                    </div>
                    <button type="submit" class="btn btn-primary h-100 ms-3 mt-auto">
                      <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-upload"></i>&nbsp; Upload</span>
                      <i class="bx bx bx-upload d-block d-sm-none"></i>
                    </button>
                  </form>
                  <div class="row" id="imageData"></div>
                </div>
              </div>
              <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered" id="paymentTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center">#</th>
                          <th class="text-center"><?php echo $th9; ?></th>
                          <th class="text-center"><?php echo $th10; ?></th>
                          <th class="text-center"><?php echo $th11; ?></th>
                          <th class="text-center"><?php echo $th12; ?></th>
                          <th class="text-center"><?php echo $th13; ?></th>
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                      <tfoot>
                        <tr>
                          <td colspan="4" style="text-align : right"><strong><?php echo $th14; ?></strong></td>
                          <td class="fw-bolder" id="totalpayment"></td>
                          <td></td>
                        </tr>
                        <tr>
                          <td colspan="4" style="text-align : right"><strong><?php echo $th15; ?></strong></td>
                          <td class="fw-bolder" id="remainpayment"></td>
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
    <script src="../script/page_main.js"></script>
    <script src="../script/page_expense_payment.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Expense");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-Expense Payment");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>
