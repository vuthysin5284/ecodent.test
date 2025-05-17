<?php
  session_start();
  $log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
  $icode = $_GET['icode'];
  $pmid = $_GET['pmid'];
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
      $page = 'Invoices';
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
      // setting
      $rowt = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM tbl_system_setting where id ='".$_SESSION["system_id"]."'")); 
    ?>
    <div class="invoice-wrapper">
      <div class="invoice-container position-relative">
        <header class="mb-0">
          <div class="d-flex align-items-center justify-content-between">
            <img id="logo" src="../assets/img/icons/brands/<?=$rowt["logo"]?>" title="malis" alt="malis" height="100">
            <div class="text-body">
              <h3 class="text-center" style="font-family: Khmer OS Muol Light; color : #000;font-size: 1.35rem;"><?=$rowt["system_name_kh"]?></h3>
              <h5 class="text-center fw-bolder mb-2" style="color : #000;"><?=$rowt["system_name_en"]?></h5>
            </div>
            <img src='https://qrcode.tec-it.com/API/QRCode?data=<?php echo urlencode('INV'.$icode); ?>' title="qr-code" alt="qr-code" height="60">
          </div>
          <!-- <h6 class="text-center fw-bolder mt-2 mb-2" style="color : #000;">[ ប៉ះធ្មេញ ព្យាបាលធ្មេញ ដក-ដាក់ធ្មេញគ្រប់ប្រភេទ ពត់តម្រង់ធ្មេញ និងដាំបង្គោលក្នុងឆ្អឹង ]</h6> -->
          
          <div class="row align-items-top" style="margin-top:50px">
            <div class="col-12 text-body text-dark">លេខអត្តសញ្ញាណកម្ម / VAT TIN : &nbsp; <strong class="text-dark"><?=$rowt["tax_no"]?></strong></div>
            <!-- <div class="col-12 text-body text-dark">លេខរៀងប្រកាសពន្ធ / TAX No : <strong class="text-dark"></strong></div>  -->
          </div>
          <hr class="me-0">
          <div id="getCustomer"></div>
          
        </header>
        <hr class="me-0">
        <h4 class="text-center text-dark fw-bolder mb-2 mt-4">វិក្ក័យបត្រ</h4>
        <h5 class="text-center text-dark fw-bold">INVOICE</h5>
        <main>
          <div class="row mb-2">
            <div class="col-12">
              <table class="table table-bordered text-dark">
                <thead class="text-nowrap table-secondary">
                  <tr>
                    <th class="text-center text-dark fw-bolder">#</th>
                    <th class="text-left text-dark fw-bolder">ការព្យាបាល</th>
                    <th class="text-center text-dark fw-bolder" width="125px">លេខធ្មេញ</th>
                    <th class="text-center text-dark fw-bolder">ចំនួន</th>
                    <th class="text-center text-dark fw-bolder">តម្លៃ</th>
                    <th class="text-center text-dark fw-bolder">បញ្ចុះតម្លៃ</th>
                    <th class="text-center text-dark fw-bolder">សរុប</th>
                  </tr>
                </thead>
                <tbody class="text-nowrap" id="invTable"></tbody>
              </table>
            </div>
          </div>
          <p class="text-body text-center"><?=$rowt["remark_invoice"]?></p>
        </main>
        <footer class="position-absolute bottom-0 pb-3" style="width : 92.5%;">
          <div class="row">
            <div class="col"><p class="text-center text-dark">អតិថិជន / Patient</p></div>
            <div class="col"></div>
            <div class="col"><p class="text-center text-dark">បេឡាករ / Cashier</p></div>
          </div>
          <br>
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
        var icode = '<?php echo $icode; ?>';
        $.ajax({
          url : '../data/query_print_invoice_payment.php',
          type : 'POST',
          data : {qid : 1, icode : icode},
          success : function(data) {
            $('#getCustomer').html(data);
          }
        });  
      }

      function getDataTable() {
        var icode = '<?php echo $icode; ?>';
        var pmid = '<?php echo $pmid; ?>';
        $.ajax({
          url : '../data/query_print_invoice_payment.php',
          type : 'POST',
          data : {qid : 2, icode : icode, pmid : pmid},
          success : function(data) {
            $('#invTable').html(data);
          }
        });  
      }
    </script>
  </foot>
</html>