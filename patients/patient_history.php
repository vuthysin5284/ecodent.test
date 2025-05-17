<?php
  session_start();
  $page = 'Medical History';
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
        $page_category = 'Patient';
        $page_title = 'Medical History';
        $th2 = 'Medical History';
        $th3 = 'Yes';
        $th4 = 'No';
      } else {
        $page_category = 'អតិថិជន';
        $page_title = 'ប្រវត្តិជំងឺទូទៅ';
        $th2 = 'ប្រវត្តិជំងឺទូទៅ';
        $th3 = 'មាន';
        $th4 = 'អត់';
      }
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
    <input type="hidden" id="cid" name="cid" value="<?php echo $cid; ?>" class="form-control" readonly/>
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
               <div class="d-flex justify-content-between">
            <div class="me-auto">
              <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">Patient /</span> Medical History
              </h4>
            </div>
            <div class="py-3">
              <button class="btn btn-primary" id="createHistory"><i class="bx bx-plus me-1"></i> Add Medical History</button>
            </div>
          </div> 
          <?php include_once ('../inc/patient_menu.php'); ?>
          <div class="card">
            <div class="card-body">
              <table class="table" id="medicalHistoryTable" width="100%">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>Patient</th>
                    <th>Date</th>
                    <th>Condition</th>
                    <th>Allergies</th>
                    <th>Medications</th>
                    <th>Notes</th>
                    <th>Actions</th>
                  </tr>
                </thead>
                <tbody></tbody>
              </table>
            </div>
          </div>

          <!-- Modal for Add/Edit -->
          <div class="modal fade" id="medicalHistoryModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
              <div class="modal-content">
                <form id="medicalHistoryForm">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalTitle">Add Medical History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" id="historyId" name="historyId">
                    <div class="row">
                      <div class="col-md-6 mb-3">
                        <label for="patientId" class="form-label">Patient</label>
                        <select id="patientId" name="patientId" class="form-select" required>
                          <option value="">Select Patient</option>
                          <?php
                            $patients = $CON->query("SELECT id, cust_fname as name FROM tbl_customer ORDER BY id ASC");
                            while($row = $patients->fetch_assoc()) {
                              echo '<option value="'.$row['id'].'">'.$row['name'].'</option>';
                            }
                          ?>
                        </select>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="date" class="form-label">Date</label>
                        <input type="date" id="date" name="date" class="form-control" required>
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="condition" class="form-label">Medical Condition(s)</label>
                        <input type="text" id="condition" name="condition" class="form-control" placeholder="e.g. Diabetes, Hypertension">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="allergies" class="form-label">Allergies</label>
                        <input type="text" id="allergies" name="allergies" class="form-control" placeholder="e.g. Penicillin, Latex">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="medications" class="form-label">Medications</label>
                        <input type="text" id="medications" name="medications" class="form-control" placeholder="e.g. Aspirin, Insulin">
                      </div>
                      <div class="col-md-6 mb-3">
                        <label for="notes" class="form-label">Notes</label>
                        <textarea id="notes" name="notes" class="form-control" rows="2" placeholder="Additional notes"></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                  </div>
                </form>
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
    <script src="../script/medical-history.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Patients");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active"); 

        var activeTabMenu = document.getElementById("patient-medical");
        activeTabMenu.classList.add("active"); 
      };
    </script>
  </foot>
</html>