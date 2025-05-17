<?php
  $page = 'Appointment';
  session_start();
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
        $page_title = 'Appointment';
        $text_btn_create = 'New';
        $text_form = 'Appointment';
        $text_form_description = '* Patient\'s ID, and Qr Code is auto generated!';
        $m1 = 'Date & Time';
        $m2 = 'Duration';
        $m3 = 'Dentist';
        $m4 = 'Repeat Appointment';
        $m5 = 'Notes';
        $th2 = 'Date & Time';
        $th3 = 'Duration';
        $th4 = 'Dentist';
        $th5 = 'Notes';
        $th6 = 'Action';
      } else {
        $page_category = 'អតិថិជន';
        $page_title = 'ការណាត់ជួប';
        $text_btn_create = 'បង្កើតថ្មី';
        $text_form = 'ការណាត់ជួប';
        $text_form_description = '* លេខកូដ និង Qr Code ត្រូវបានបង្កើតដោយស្វ័យប្រវត្តិ!';
        $m1 = 'ពេលវេលា';
        $m2 = 'រយៈពេល';
        $m3 = 'ទន្តបណ្ឌិត';
        $m4 = 'ណាត់ជួបបន្តបន្ទាប់';
        $m5 = 'សំគាល់';
        $th2 = 'ពេលវេលាណាត់ជួប';
        $th3 = 'រយៈពេល';
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
              </div>              -->
              
              <div class="card">
                <?php include_once ('../inc/patient_menu.php'); ?>
                <div class="card-body"  style="width:80%; margin:auto"> 
                
                  <div class="d-flex justify-content-between">
                    <div class="me-auto">
                      <h4 class="fw-bold py-3 mb-4"><span class="text-muted fw-light">Profiles</h4>
                    </div>
                  </div>

                  <?php echo inpImage('image', '../images/profiles/0.jpg'); ?>
                  <div class="row mb-3">
                    <div class="col">
                      <label for="id" class="form-label"><?php echo $m1; ?></label>
                      <div class="input-group input-group-merge">
                        <input type="text" class="form-control" id="id" name="id" />
                        <button type="button" class="input-group-text cursor-pointer" id="editPatientID" value='0'><i id='iconPatientID'></i></button>
                      </div>   
                    </div>
                    <?php //echo inpText($m1, 'id', '','1'); ?>
                    <?php echo inpText($m2, 'code', '','2'); ?>
                    <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_description; ?></div>
                  </div>
                  <div class="row mb-3"><?php echo selectData($m3, 'membership', 'SELECT * FROM tbl_membership ORDER BY memb_discount ASC', 'memb_type'); ?></div>
                  <div class="row mb-3"><?php echo inpText($m4, 'name', 'Ex: Sun Kimhuon'); ?></div>
                  <div class="row mb-3"> 
                    <?php echo selectGender($m5, 'gender'); ?>
                    <?php echo inpDate($m6, 'dob', ''); ?>
                    <?php echo inpNum($m12, 'age', ''); ?>
                  </div>
                  <div class="row mb-3">
                    <?php echo inpText($m7, 'contact', 'Ex: 012 345 678', 0); ?>
                    <?php echo inpText($m11, 'email', 'Ex: me@mail.com', 0); ?>
                  </div>
                  <div class="row mb-3"><?php echo inpText($m8, 'address', 'Ex: Toul Kork, Phnom Penh', 0); ?></div>
                  <div class="row mb-3"><?php echo selectData($m9, 'dentist', 'SELECT * FROM `tbl_staff` WHERE `id` > 1 AND `staff_status` = 1 ORDER BY `id` ASC', 'staff_fname'); ?></div>
                  <div class="row mb-3"><?php echo inpDateTime($m10, 'datetime'); ?></div>
                
                  <!--  -->
                  <div class="card-footer"> 
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
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
    <script src="../script/page_patient_appointment.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Patients");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("Patient List");
        activeSubMenu.classList.add("active");

        var activeTabMenu = document.getElementById("patient-personal");
        activeTabMenu.classList.add("active");
      };
    </script>
  </foot>
</html>