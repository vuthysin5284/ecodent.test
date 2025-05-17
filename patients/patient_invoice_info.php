<?php
  session_start();
  $page = 'Invoice';
  $log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
  $cid = $_GET['cid']; 
  $invid = $_GET['invid'];
  $pgid = $_GET['pgid']; 
  $apid = $_GET['apid']; 
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
        $page_category = 'Patients';
        $page_title = 'Invoice';
        $text_btn_create = 'New';
        $m1 = 'Invoice No.';
        $m2 = 'Category';
        $m3 = 'Dentist';
        $m4 = 'Status';
        $th2 = 'Treatment Description';
        $th3 = 'Tooth No.';
        $th4 = 'Qty';
        $th5 = 'Price ($)';
        $th6 = 'Disc (%)';
        $th7 = 'Total ($)';
        $th8 = 'Sub Total :';
        $th9 = 'Discount :';
        $th10 = 'Grand Total :';
        $th11 = 'Date & Time';
        $th12 = 'Remark';
        $th13 = 'Method';
        $th14 = 'Amount ($)';
        $th15 = 'Action';
        $th16 = 'Total Payment ($)';
        $th17 = 'Remain Payment';
      } else {
        $page_category = 'អតិថិជន';
        $page_title = 'វិក្កយបត្រ';
        $text_btn_create = 'បង្កើតថ្មី';
        $m1 = 'លេខវិក្កយបត្រ';
        $m2 = 'ប្រភេទសេវាកម្ម';
        $m3 = 'ទន្តបណ្ឌិត';
        $m4 = 'សំគាល់';
        $th2 = 'សេវាកម្មព្យាបាល';
        $th3 = 'លេខធ្មេញ';
        $th4 = 'ចំនួន';
        $th5 = 'តម្លៃ ($)';
        $th6 = 'បញ្ចុះតម្លៃ (%)';
        $th7 = 'សរុប ($)';
        $th8 = 'តម្លៃសរុប :';
        $th9 = 'បញ្ចុះតម្លៃ :';
        $th10 = 'សរុប :';
        $th11 = 'កាលបរិច្ឆេទ';
        $th12 = 'សំគាល់';
        $th13 = 'តាមរយៈ';
        $th14 = 'ចំនួនទឹកប្រាក់ ($)';
        $th15 = 'ដំណើរការ';
        $th16 = 'ទូទាត់រួចរាល់';
        $th17 = 'នៅសល់';
      }
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
    <input type="hidden" id="cid" name="cid" value="<?php echo $cid; ?>" class="form-control" required readonly/>
    <input type="hidden" id="invid" name="invid" value="<?php echo $invid; ?>" class="form-control" required readonly/>
    <?php
      $CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_customer` WHERE `cust_code` = '$cid' LIMIT 1"));
      $id = $CUST['id'];
      $code = $CUST['cust_code'];
      $fname = $CUST['cust_fname'];
      $age = (date('Y') - date('Y', strtotime($CUST['cust_dob'])));
      $custId = 'P-'. sprintf('%05d', $CUST['id']);
      $folder = ($CUST['cust_image'] == '0') ? '' : $custId.'/';
      $gender = ($CUST['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
      $image = $CUST['cust_image'];
      $contact = $CUST['cust_contact'];
      $address = $CUST['cust_address'];
      $image_path = '../images/profiles/'.$folder.''.$image.'.jpg';
    ?>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="card mb-4">
                <div class="card-body">
                  <div class="d-flex align-items-start align-items-sm-center gap-4">
                    <?php
                      echo '<img src="'.$image_path.'" alt="user-avatar" class="d-block rounded" height="100" width="100" id="uploadedAvatar"/>';
                      echo '<div class="button-wrapper">';
                      echo '<h4 class="body-text mb-2">'.$fname.'</h4>'.$gender.'&nbsp;&nbsp; - &nbsp;&nbsp;'.$age.' Years <br><i class="bx bxs-phone-call mb-2 mt-2"></i> '.$contact.'<br><i class="bx bxs-map"></i> '.$address;
                      echo '</div>';
                    ?>
                  </div>
                </div>
              </div>
              <?php include_once ('../inc/patient_menu.php'); ?>
              <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div>
              </div>
              <?php 
                $STATUS = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `inv_status` FROM `tbl_invoice_patient` WHERE `id` = '$invid' LIMIT 1"));
                $status = $STATUS['inv_status'];
                if($status == 1) {
              ?>
              <div class="card mb-4">
                <div class="card-body p-3">
                  <form id="addForm" method="POST" enctype="multipart/form-data">
                    <div class="row p-2">
                      <div class="col-xl-3 col-md-3 col-12 mb-2">
                        <label for="id" class="form-label"><?php echo $m1; ?></label>
                        <div class="input-group input-group-merge">
                          <input type="text" class="form-control" id="title" name="title" />
                          <button type="button" class="input-group-text cursor-pointer" id="editInvoice" value='0'><i id='editIcon'></i></button>
                        </div>
                        
                        <input type="hidden" id="id" name="id" class="form-control" required readonly/>
                        <input type="hidden" id="grandtotal" name="grandtotal" class="form-control" required readonly/>
                      </div>
                      <div class="col-xl-3 col-md-3 col-12 mb-2">
                        <label for="dentist" class="form-label"><?php echo $m3; ?></label>
                        <select class="form-select" id="dentist" name="dentist" required >
                          <option hidden value="">--- select item ---</option>
                          <?php
                            $SQL = "SELECT * FROM `tbl_staff` WHERE `id` > 1 AND `staff_status` = 1 ORDER BY `id` ASC";
                            $QUERY = mysqli_query($CON, $SQL);
                            while ($ROW = mysqli_fetch_assoc($QUERY)) {
                              $id = $ROW['id'];
                              $staff_name = $ROW['staff_fname'];
                              echo '<option value="'.$id.'">'.$staff_name.'</option>';
                            }
                          ?>
                        </select>
                      </div>
                      <div class="col-xl-3 col-md-3 col-12 mb-2">
                        <label for="status" class="form-label"><?php echo $m4; ?></label>
                        <select class="form-select" id="status" name="status" required >
                          <option value="1">QUOTE</option>
                          <option value="2">Pending</option>
                          <option value="3">Completed</option>
                        </select>
                      </div>
                      <div class="col-xl-3 col-md-3 col-12 mb-2 mt-auto">
                        <button type="submit" class="btn btn-primary">Save</button>
                      </div>
                    </div>
                  </form>
                </div>
              </div>
              <?php } ?>
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
            <?php include_once('../inc/footage.php'); ?> 
          </div>
        </div>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?> 
    <script src="../script/page_main.js"></script>
    <script src="../script/page_patient_invoice_info.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeSubMenu = document.getElementById("patient-invoice");
        activeSubMenu.classList.add("active");
      };
    </script>
  </foot>
</html>