<?php
  session_start();
  $log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
  $pgid = $_GET['pgid'];
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
      $page = 'Prescription';
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
    <style type="text/css">
      .invoice-wrapper { 
        width : 847px;
        height : 1190px;
        margin : 0px auto;
        background-color : #fff;
      }
      .invoice-container {
        padding : 30px;
        height : 100%;
      }
    </style>
  </head>
  <body>
    <?php
      $QR = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `pres_code` FROM `tbl_diagnosis` WHERE`id` = '$pid' LIMIT 1"));
      $data = $QR['pres_code'];
      
      // setting
      $rowt = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM tbl_system_setting where id ='".$_SESSION["system_id"]."'")); 
    ?>
    <div class="invoice-wrapper">
      <div class="invoice-container position-relative">
        <header class="mb-0">
          <div class="d-flex align-items-center justify-content-between">
            <img id="logo" src="../assets/img/icons/brands/<?=$rowt["logo"]?>" title="malis" alt="malis" height="100">
            <div class="text-body">
              <h3 class="text-center" style="font-family: Khmer OS Muol Light; color : #000;"><?=$rowt["system_name_kh"]?></h3>
              <h5 class="text-center fw-bolder mb-0" style="color : #000;"><?=$rowt["system_name_en"]?></h5>
            </div>
            <img src='https://qrcode.tec-it.com/API/QRCode?data=<?php echo urlencode('PRE'.$data); ?>' title="dentyst" alt="dentyst" height="60">
          </div>
          <hr class="me-0">
          <div id="getCustomer"></div>
        </header>
        <hr class="me-0">
        <h4 class="text-center text-dark fw-bolder mb-2 mt-4">វេជ្ជបញ្ជា</h4>
        <p class="text-center text-dark">PRESCRIPTION</p>
        <main>
          <table class="table table-bordered">
            <thead class="text-nowrap table-secondary">
              <tr>
                <th class="text-center text-dark fw-bolder">#</th>
                <th class="text-center text-dark fw-bolder">រាយនាមឱសថ</th>
                <th class="text-center text-dark fw-bolder">ចំនួន</th>
                <th class="text-center text-dark fw-bolder">ការប្រើប្រាស់</th>
                <th class="text-center text-dark fw-bolder">រយៈពេល</th>
              </tr>
            </thead>
            <tbody class="text-nowrap text-dark fw-bolder" id="presTable"></tbody>
          </table>
          <p class="text-body">* បើពិសាថ្នាំទៅមានបញ្ហា សូមឈប់ប្រើ និងត្រលប់មកវិញ ហើយសូមយកវេជ្ជបញ្ជានេះមកជាមួយពេលពិនិត្យលើកក្រោយ។</p>
        </main>
        <footer class="position-absolute bottom-0 pb-3" style="width : 92.5%;">
          <div class="row">
            <div class="col"></div>
            <div class="col"></div>
            <div class="col">
              <p class="text-center text-dark">ទន្តបណ្ឌិត / Dentist</p>
              <br><br><br>
            </div>
          </div>
          <hr class="me-0">
          <div class="d-flex align-items-center justify-content-between">
            <p class="text-body"><?=$rowt["address"]?></p>
            <p class="text-body"><?=$rowt["phone"]?></p>
          </div>
        </footer>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?> 
    <script type="text/javascript">
      $(document).ready(function() {
        getCustomer();
        getDataTable();
      });
      
      function getCustomer() {
        var pid = '<?php echo $pid; ?>';
        $.ajax({
          url : '../data/query_print_prescription.php',
          type : 'POST',
          data : {qid : 1, pid : pid},
          success : function(data) {
            $('#getCustomer').html(data);
          }
        });  
      }

      function getDataTable() {
        var pid = '<?php echo $pid; ?>';
        $.ajax({
          url : '../data/query_print_prescription.php',
          type : 'POST',
          data : {qid : 2, pid : pid},
          success : function(data) {
            $('#presTable').html(data);
            // window.print();
          }
        });  
      }
    </script>
  </foot>
</html>