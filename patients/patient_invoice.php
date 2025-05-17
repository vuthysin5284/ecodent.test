<?php
  session_start();
  $page = 'Invoice';
  $log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
  $cid = $_GET['cid']; 
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
        $th2 = 'Timestamp';
        $th3 = 'Remark';
        $th4 = 'Dentist';
        $th5 = 'Status';
        $th6 = 'Action';
      } else {
        $page_category = 'អតិថិជន';
        $page_title = 'វិក្កយបត្រ';
        $text_btn_create = 'បង្កើតថ្មី';
        $th2 = 'ពេលវេលា';
        $th3 = 'ចំណងជើង';
        $th4 = 'ទន្តបណ្ឌិត';
        $th5 = 'សំគាល់';
        $th6 = 'ដំណើរការ';
      }
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
    <input type="hidden" id="cid" name="cid" value="<?php echo $cid; ?>" class="form-control" required readonly/>
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
              <!-- <div class="card mb-4">
                <div class="card-body">  
                  < ?php include_once ('../inc/patient_short_profile.php'); ?>
                </div>
              </div> -->
              
              <div class="card">
                <?php include_once ('../inc/patient_menu.php'); ?>
                <div class="card-body p-3">
                  <div class="d-flex justify-content-between">
                    <div class="me-auto">
                        <h4 class="fw-bold py-3 mb-4">
                        <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                        </h4>
                    </div>
                  </div>
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
    <script src="../script/page_patient_invoice.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Patients");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active"); 

        var activeTabMenu = document.getElementById("patient-invoice");
        activeTabMenu.classList.add("active");  
      };
    </script>
  </foot>
</html>