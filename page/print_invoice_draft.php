<?php
  session_start();
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
      $page = 'Invoices';
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
    <style type="text/css"> 
      @page { size: A5; margin: 0;font-family: Aeonik, sans-serif  !important;}
      .invoice-wrapper { 
        /* width : 847px;
        height : 1190px;
        background-color : #fff;  */
        width: 21cm;
        min-height: 29.7cm;
        padding: 0cm;
        /* margin: 0cm auto;   */
        margin : 0px auto;
        background: white; 
      }
      .invoice-container {
        padding : 30px;
        height : 100%; 
      } 
         
    </style>
  </head>
  <body >
    <?php
      $INV = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id`, `staff_id` FROM `tbl_invoice_patient` WHERE `inv_code` = '$icode' LIMIT 1"));
      $invid = $INV['id'];
      $staff_id = $INV['staff_id'];
      $STAFF = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_fname` FROM `tbl_staff` WHERE `id` = '$staff_id' LIMIT 1"));
      $dentist = $STAFF['staff_fname'];
      $invoice_id = 'INV-'. sprintf('%05d', $invid);
      $CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `c`.* FROM `tbl_customer` AS `c` INNER JOIN `tbl_invoice_patient` AS `ip` ON (`ip`.`cust_id` = `c`.`id`) WHERE `ip`.`id` = '$invid' LIMIT 1"));
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

      // setting
      $rowt = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM tbl_system_setting where id ='".$_SESSION["system_id"]."'")); 
    ?>
    <div class="invoice-wrapper" id="htmlContent">
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
          <!-- <h6 class="text-center fw-bolder mt-2 mb-2" style="color : #000;">[ ប៉ះធ្មេញ ព្យាបាលធ្មេញ ថ្នាំការពារធ្មេញស្រុករបស់កុមារ ដាំបង្គោលធ្មេញ ពត់តម្រង់ធ្មេញ ធ្វើធ្មេញឲ្យស និងដាក់ធ្មេញគ្រប់ប្រភេទ ]</h6> -->
          <hr class="me-0">
          <div id="getCustomer"></div>
        </header>
        <hr class="me-0">
        <h4 class="text-center text-dark fw-bolder mb-2 mt-4">វិក្ក័យបត្រ</h4>
        <p class="text-center text-dark">INVOICE</p>
        <!-- <p class="text-center text-dark">QUOTATION</p> -->
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
        <footer class=" bottom-0 pb-3" style="width : 100%;margin-top:200px">
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
          <!-- <a href="javascript:void(0);" class="btn btn-primary" id="save">JPG</a> -->
        </footer>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script type="text/javascript">
      $(document).ready(function() {
        getCustomer();
        getDataTable();
      });

      function getCustomer() {
        var icode = '<?php echo $icode; ?>';
        $.ajax({
          url : '../data/query_print_invoice_draft.php',
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
          url : '../data/query_print_invoice_draft.php',
          type : 'POST',
          data : {qid : 2, icode : icode},
          success : function(data) {
            $('#invTable').html(data);
          }
        });  
      }
    </script>
    <!-- <script>
      document.getElementById("save").onclick = function() {
        const content = document.getElementById('htmlContent');
        html2canvas(content).then((canvas) => {
          const base64image = canvas.toDataURL("image/png");
          var anchor = document.createElement('a');
          anchor.setAttribute("href", base64image);
          anchor.setAttribute("download", "id-card.png");
          anchor.click();
          anchor.remove();
        });
      };
    </script> -->
    </foot>
</html>