<?php
  session_start();
  $page = 'Payment';
  $log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
  $icode = $_GET['icode'];
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
        $page_title = 'Pending Invoices';
        $text_btn_create = 'Create Payment';
        $text_form = 'Invoice Payment';
        $m1 = 'Amount (USD)';
        $m2 = 'Amount (KHR)';
        $m3 = 'Change (USD)';
        $m4 = 'Change (KHR)';
        $m5 = 'Total (USD)';
        $m6 = 'Total (KHR)';
        $m7 = 'Payment Method';
        $m8 = 'Remark';
        $m9 = 'Date & Time';
        $th2 = 'Treatment Description';
        $th3 = 'Tooth No.';
        $th4 = 'Qty';
        $th5 = 'Price';
        $th6 = 'Disc';
        $th7 = 'Total';
        $th8 = 'Sub Total :';
        $th9 = 'Discount :';
        $th10 = 'Grand Total :';
        $th11 = 'Date & Time';
        $th12 = 'Remark';
        $th13 = 'Method';
        $th14 = 'Amount ($)';
        $th15 = 'Action';
        $th16= 'Total Payment :';
        $th17 = 'Remain Payment :';
      } else {
        $page_category = 'វិក្កយបត្រ';
        $page_title = 'ការបង់ប្រាក់';
        $text_btn_create = 'បង់ប្រាក់';
        $text_form = 'បញ្ចូលការបង់ប្រាក់';
        $m1 = 'ចំនួនទឹកប្រាក់ (USD)';
        $m2 = 'ចំនួនទឹកប្រាក់ (KHR)';
        $m3 = 'ប្រាក់អាប់ (ដុល្លា)';
        $m4 = 'ប្រាក់អាប់ (រៀល)';
        $m5 = 'សរុប (ដុល្លា)';
        $m6 = 'សរុប (រៀល)';
        $m7 = 'តាមរយៈ';
        $m8 = 'សំគាល់';
        $m9 = 'កាលបរិច្ឆេទ';
        $th2 = 'សេវាព្យាបាល';
        $th3 = 'លេខធ្មេញ';
        $th4 = 'ចំនួន';
        $th5 = 'តម្លៃ';
        $th6 = 'បញ្ចុះតម្លៃ';
        $th7 = 'សរុប';
        $th8 = 'តម្លៃសរុប :';
        $th9 = 'បញ្ចុះតម្លៃ :';
        $th10 = 'សរុប :';
        $th11 = 'ពេលវេលា';
        $th12 = 'សំគាល់';
        $th13 = 'តាមរយៈ';
        $th14 = 'ចំនួនទឹកប្រាក់ ($)';
        $th15 = 'ដំណើរការ';
        $th16= 'ទឹកប្រាក់បានបង់ :';
        $th17 = 'ទឹកប្រាក់នៅខ្វះ :';
      }
      include_once('../inc/config.php');
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
              <div class="row">
                <div class="col-md-12">
                  <div class="card mb-4">
                    <div class="card-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4">
                        <?php
                            $row = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `c`.*,`i`.inv_remain,`i`.inv_grandtotal FROM `tbl_customer` AS `c` INNER JOIN `tbl_invoice_patient` AS `i` ON (`i`.`cust_id` = `c`.`id`) WHERE `inv_code` = '$icode' LIMIT 1"));
                            $id = $row['id'];
                            $code = $row['cust_code'];
                            $fname = $row['cust_fname'];
                            $age = (date('Y') - date('Y', strtotime($row['cust_dob'])));
                            $custId = 'P-'. sprintf('%05d', $row['id']);
                            $folder = ($row['cust_image'] == '0') ? '' : $custId.'/';
                            $gender = ($row['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
                            $image = $row['cust_image'];
                            $contact = $row['cust_contact'];
                            $address = $row['cust_address'];
                            $image_path = '../images/profiles/'.$folder.''.$image.'.jpg';
                            echo '<img src="'.$image_path.'" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar"/>';
                            echo '<div class="button-wrapper">';
                            echo '<h4 class="body-text mb-2">'.$fname.'</h4>'.$gender.'&nbsp;&nbsp; - &nbsp;&nbsp;'.$age.' Years <br><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$contact.'<br><i class="bx bxs-map"></i> '.$address;
                             
                            
                            // is paid or patial paid 
                            $inv_remain = $row["inv_remain"];

                          ?>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-md-12">
                  <div class="d-flex justify-content-between">
                    <div class="me-auto">
                      <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                      </h4>
                    </div>
                  <?php if ($nav_staff_position_id == 1 || $nav_staff_position_id == 2) { ?>
                    <div class="p-2">
                      <?php if($inv_remain>0){ ?>
                        <!--?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?-->
                          <button type="button" id="create" class="btn btn-primary" >
                            <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; <?php echo $text_btn_create; ?></span>
                              <i class="bx bx bx-plus d-block d-sm-none"></i>
                          </button>
                        <!--?php } ?-->
                      <?php } else{ echo '<span class="btn btn-info tf-icons d-none d-sm-block">Invoice Paid </span>';} ?>
                    </div>
                  <?php } ?>
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
                <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 500px;" tabindex="-1" id="invoicePaymentOffcanvas" 
                  aria-labelledby="invoicePaymentOffcanvasLabel"> 
                  <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="invoicePaymentOffcanvasLabel"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                  <form  id="addForm" method="POST" enctype="multipart/form-data"> 
                        <input type="hidden" id="inv_paym_id" name="inv_paym_id" class="form-control" readonly/>
                        <div class="modal-payment">
                          <div class="row mb-3">
                            <input type="hidden" id="icode" name="icode" value="<?php echo $icode; ?>" class="form-control" readonly/>
                            <div class="col">
                              <label for="amount" class="form-label"><?php echo $m1; ?></label>
                              <div class="input-group input-group-merge">
                                <span class="input-group-text fw-bolder text-primary">$</span>
                                <input type="text" class="form-control fw-bolder text-primary" name="amount_en" id="amount_en" value="0" required />
                              </div>
                              <!-- <input type="hidden" class="form-control" name="toggle" id="toggle" required readonly/> -->
                            </div>
                            <div class="col">
                              <label for="change_kh" class="form-label"><?php echo $m2; ?></label>
                              <div class="input-group input-group-merge">
                                <span class="input-group-text fw-bolder text-primary">៛</span>
                                <input type="text" class="form-control fw-bolder text-primary" name="amount_kh" id="amount_kh" value="0" required />
                              </div>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <div class="col">
                              <label for="change_en" class="form-label"><?php echo $m3; ?></label>
                              <div class="input-group input-group-merge">
                                <span class="input-group-text fw-bolder text-danger">$</span>
                                <input type="text" class="form-control fw-bolder text-danger" name="change_en" id="change_en" required readonly />
                              </div>
                            </div>
                            <div class="col">
                              <label for="change_kh" class="form-label"><?php echo $m4; ?></label>
                              <div class="input-group input-group-merge">
                                <span class="input-group-text fw-bolder text-danger">៛</span>
                                <input type="text" class="form-control fw-bolder text-danger" name="change_kh" id="change_kh" required readonly />
                              </div>
                            </div>
                          </div>
                          <div class="row mb-3">
                            <div class="col">
                              <label for="remain" class="form-label"><?php echo $m5; ?></label>
                              <div class="input-group input-group-merge">
                                <span class="input-group-text fw-bolder">$</span>
                                <input type="text" class="form-control fw-bolder" name="remain" id="remain" required readonly />
                              </div>
                            </div>
                            <div class="col">
                              <label for="remain_kh" class="form-label"><?php echo $m6; ?></label>
                              <div class="input-group input-group-merge">
                                <span class="input-group-text fw-bolder">៛</span>
                                <input type="text" class="form-control fw-bolder" name="remain_kh" id="remain_kh" required readonly />
                              </div>
                            </div>
                          </div>
                          <br><hr><br>
                        </div>
                        <div class="row mb-3"><?php echo inpDateTime($m9, 'datetime'); ?></div>
                        <div class="row mb-3"><?php echo selectData($m7, 'paym', 'SELECT * FROM `tbl_payment_method` ORDER BY `payment_method` ASC', 'payment_method'); ?></div>
                        <div class="row mb-3"><?php echo inpText($m8, 'note', 'Ex: Filling #17', '0'); ?></div>
                        
                      
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
                            <th class="text-center"><?php echo $th2; ?></th>
                            <th class="text-center"><?php echo $th3; ?></th>
                            <th class="text-center"><?php echo $th4; ?></th>
                            <th class="text-center"><?php echo $th5; ?></th>
                            <th class="text-center"><?php echo $th6; ?></th>
                            <th class="text-center"><?php echo $th7; ?></th>
                          </tr>
                        </thead>
                        <tbody class="text-wrap"></tbody>
                        <tfoot>
                          <tr>
                            <td colspan="6" style="text-align : right"><strong><?php echo $th8; ?></strong></td>
                            <td class="fw-bolder" id="subTotal"></td>
                          </tr>
                          <tr>
                            <td colspan="6" style="text-align : right"><strong><?php echo $th9; ?></strong></td>
                            <td class="fw-bolder" id="totalDiscount"></td>
                          </tr>
                          <tr>
                            <td colspan="6" style="text-align : right"><strong><?php echo $th10; ?></strong></td>
                            <td class="fw-bolder" id="grandTotal"></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
                  </div>
                </div>
                <div class="card">
                  <div class="card-body p-3">
                    <div class="table-responsive p-2">
                      <table class="table table-bordered" id="paymentTable">
                        <thead class="text-nowrap">
                          <tr>
                            <th class="text-center">#</th>
                            <th class="text-center"><?php echo $th11; ?></th>
                            <th class="text-center"><?php echo $th12; ?></th>
                            <th class="text-center"><?php echo $th13; ?></th>
                            <th class="text-center"><?php echo $th14; ?></th>
                            <th class="text-center"><?php echo $th15; ?></th>
                          </tr>
                        </thead>
                        <tbody class="text-nowrap"></tbody>
                        <tfoot>
                          <tr>
                            <td colspan="4" style="text-align : right"><strong><?php echo $th16; ?></strong></td>
                            <td class="fw-bolder" id="totalpayment"></td>
                            <td></td>
                          </tr>
                          <tr>
                            <td colspan="4" style="text-align : right"><strong><?php echo $th17; ?></strong></td>
                            <td class="fw-bolder" id="remainpayment"></td>
                            <td></td>
                          </tr>
                        </tfoot>
                      </table>
                    </div>
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
    <script src="../script/page_invoice_payment.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Revenue");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-Pending Invoice"); 
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>