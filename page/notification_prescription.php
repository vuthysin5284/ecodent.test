<?php
  session_start();
  $page = 'Prescription';
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
        $page_title = 'Prescription';
        $text_btn_create = 'Add Medicine';
        $text_form = 'Prescription';
        $m1 = 'Medicine';
        $m2 = 'Category';
        $m3 = 'Morning';
        $m4 = 'Afternoon';
        $m5 = 'Evening';
        $m6 = 'Duration (Day)';
        $m7 = 'Instruction';
        $th2 = 'Medicine';
        $th3 = 'Instruction';
        $th4 = 'Morning';
        $th5 = 'Afternoon';
        $th6 = 'Evening';
        $th7 = 'Duration';
        $th8 = 'Action';
      } else {
        $page_category = 'អតិថិជន';
        $page_title = 'វេជ្ជបញ្ជា';
        $text_btn_create = 'បញ្ចូលឱសថ';
        $text_form = 'វេជ្ជបញ្ជា';
        $m1 = 'ឈ្មោះឱសថ';
        $m2 = 'ប្រភេទ';
        $m3 = 'ពេលព្រឹក';
        $m4 = 'ពេលថ្ងៃ';
        $m5 = 'ពេលល្ងាច';
        $m6 = 'រយៈពេល (ថ្ងៃ)';
        $m7 = 'ការប្រើប្រាស់';
        $th2 = 'ឈ្មោះឱសថ';
        $th3 = 'ការប្រើប្រាស់';
        $th4 = 'ពេលព្រឹក';
        $th5 = 'ពេលថ្ងៃ';
        $th6 = 'ពេលល្ងាច';
        $th7 = 'រយៈពេល';
        $th8 = 'ដំណើរការ';
      }
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
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
                    <div class="py-2">
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
                          <div class="row mb-3">
                            <input type="hidden" id="pid" name="pid" value="<?php echo $pid; ?>" class="form-control" readonly/>
                            <?php echo inpText($m1, 'medicine', 'Ex: Doliprane 500mg'); ?>
                          </div>
                          <div class="row"><div class="col"><div id="medSearch" class="list-group"></div></div></div>
                          <div class="row mb-3"><?php echo selectData($m2, 'category', 'SELECT * FROM `tbl_measurement` ORDER BY `measure_eng` ASC', 'measure_eng'); ?></div>
                          <div class="row mb-3">
                            <?php echo inpText($m3, 'morning', '1'); ?>
                            <?php echo inpText($m4, 'afternoon', '1'); ?>
                            <?php echo inpText($m5, 'evening', '1'); ?>
                          </div>
                          <div class="row mb-3"><?php echo inpText($m6, 'duration', '5'); ?></div>
                          <div class="row mb-3">
                            <div class="col">
                              <label for="instruction" class="form-label"><?php echo $m7; ?></label>
                              <select class="form-select" id="instruction" name="instruction" required >
                                <option value="0">After Meal</option>
                                <option value="1">Before Meal</option>
                              </select>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                          <button type="submit" class="btn btn-primary">Save</button>
                        </div>
                      </form>
                    </div>
                  </div>
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
                <div class="card-footer pt-0">
                  <a href="print_prescription.php?pgid=<?php echo $pid ?>" class="btn btn-primary"><i class='bx bx-printer mx-2'></i>Print</a>
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
    <script src="../script/page_notification_prescription.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Patients");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("Patient List");
        activeSubMenu.classList.add("active");

        var activeTabMenu = document.getElementById("notification-prescription");
        activeTabMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>