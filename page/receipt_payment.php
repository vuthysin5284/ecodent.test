<?php
  $page = 'Completed Invoice';
  // include_once('../inc/session.php');
  session_start();
  $log = $_SESSION['uid'];
  $clinic_id = $_SESSION['business_id'];
  $lang = 1;;
  include_once('../inc/config.php');
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
        $page_title = 'Revenues Report';
        $text_dropdown = 'Show More';
        $graph_title = 'Revenues Report';
        $th1 = 'Invoice No';
        $th2 = 'Image';
        $th3 = 'Patient';
        $th4 = 'Total ($)';
        $th5 = 'Remain ($)';
        $th6 = 'Status';
        $th7 = 'Dentist';
        $th8 = 'Invoice date';
      } else {
        $page_category = 'របាយការណ៍';
        $page_title = 'វិក្កយបត្រ';
        $text_dropdown = 'ពត៌មានបន្ថែម';
        $graph_title = 'របាយការណ៍ចំណូល';
        $th1 = 'លេខវិក្កយបត្រ';
        $th2 = 'រូបភាព';
        $th3 = 'អតិថិជន';
        $th4 = 'ទឹកប្រាក់សរុប ($)';
        $th5 = 'នៅសល់ ($)';
        $th6 = 'សំគាល់';
        $th7 = 'ទន្តបណ្ឌិត';
        $th8 = 'កាលបរិច្ឆេទ';
      }
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
              <?php //include_once('../inc/invoice_menu.php'); ?>
              <div class="row justify-content-between">
                <div class="me-auto col-lg-9 col-md-7 col-sm-12 col-12">
                  <h4 class="fw-bold py-2 mb-3">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> / </span><?php echo $page_title; ?>
                  </h4>
                </div>
                <div class="d-flex h-100 col-lg-3 col-md-5 col-sm-12 col-12 mb-4">
                  <div class="input-group input-group-merge" id="reportrange">
                    <span class="input-group-text"><i class="bx bx-calendar"></i></span>
                    <input type="text" id="date" class="form-control bg-white text-center" readonly />
                  </div>
                  <input type="hidden" id="str" class="form-control bg-white text-center" readonly />
                  <button type="button" class="btn btn-icon btn-primary ms-2" id="btnFilter"><i class="bx bx-filter-alt"></i></button> 
                </div> 
              </div>
              <!-- btn -->
              <!-- <div class="d-flex justify-content-between">
                <div class="me-auto"> </div>
                <div class="py-2"> 
                  < ?php if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                    <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                      <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; Create Receive Payment</span>
                      <i class="bx bx bx-plus d-block d-sm-none"></i>
                    </button>
                  < ?php } ?> 
                </div>
              </div> -->
              <!-- form -->
              <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog" style="max-width: 99%;" >
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle">Form receive payment</h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> 
                      <div class="mb-3 row">
                        <div class="col-sm-3">   
                          <div class="col-12"> 
                              <?php echo inpDate("Entry date", 'entry_date', ''); ?>
                              <?php echo inpDate("Post date", 'post_date', ''); ?> 
                              <?php echo inpTextarea("Remark", 'remark', ''); ?>  
                          </div>

                        </div>
                        <div class="col-sm-9"> 
                          <label for="dataPaymentListTable" class="form-label">Payment List</label>
                          <div class='table-responsive'>
                            <table class="table table-bordered" id="dataPaymentListTable" style="width: 100%;" >
                              <thead>
                                <tr>
                                  <th class="text-center">Check</th> 
                                  <th class="text-center">Invoice No</th>
                                  <th class="text-left">Patient</th>
                                  <th class="text-left">Dentist</th>
                                  <th class="text-center">Amount ($)</th>
                                  <th class="text-center">Method</th>
                                  <th class="text-left">Created By</th>
                                  <th class="text-center">Date</th>
                                  <th class="text-center">Payment ID</th>
                                </tr>
                              </thead>
                              <tbody class="text-nowrap"></tbody>
                            </table>
                          </div>

                        </div>
                      </div> 
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary" id="btnSubmitReceivedPayment">Submit Receive Payment</button>
                    </div>
                  </form>
                </div>
              </div>

              <div class="row">
                <div class="col-lg-6 col-md-12">
                  <div class="row" id="dataReport"></div>
                </div>
                <div class="col-12 col-lg-6 mb-4"> test </div>
                <!-- <div class="col-12 col-lg-6 mb-4">
                  <div class="card h-100">
                    <div class="row row-bordered g-0">
                      <div class="col-md-12">
                        <h5 class="card-header m-0 me-2 pb-3"><?php echo $graph_title; ?></h5>
                        <div id="totalRevenueChart" class="px-2"></div>
                      </div>
                    </div>
                  </div>
                </div> -->
              </div>

              <!-- list -->
              <div class="card">
                <div class="table-responsive text-nowrap p-4 me-md-2 me-sm-4 me-4">
                  <div class="row">
                    <!-- <table class="table table-bordered" id="dataTable">
                      <thead>
                        <tr>
                          <th class="text-center"></th> 
                          <th class="text-center">#</th> 
                          <th class="text-center">Entry date</th>
                          <th class="text-center">Post date</th>
                          <th class="text-center">Received by</th>
                          <th class="text-center">Amount</th>
                          <th class="text-center">Count</th>
                          <th class="text-left w-50">Remark</th>
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                    </table> -->
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center"><?php echo $th1; ?></th> 
                          <th class="text-left w-25"><?php echo $th3; ?></th>
                          <!-- <th class="text-center"><?php echo $th4; ?></th> -->
                          <!-- <th class="text-center"><?php echo $th5; ?></th> -->
                          <th class="text-center">Paid ($)</th>
                          <th class="text-center">PaidBy</th>
                          <!-- <th class="text-center">< ?php echo $th6; ?></th> -->
                          <th class="text-left w-25"><?php echo $th7; ?></th>
                          <th class="text-center  w-25"><?php echo $th8; ?></th>
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                      <tfoot>
                        <tr>
                          <td colspan="2"></td> 
                          <td class="fw-bolder" id="paidTotal"></td>
                          <td colspan="3"></td>
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
    <script src="../script/page_receipt_payment.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Revenue");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-Receipt Payment");
        activeSubMenu.classList.add("active");
        // var activeSubMenu = document.getElementById("invoice-complete");
        // activeSubMenu.classList.add("active");
      
        // report
        let cardColor, headingColor, axisColor, shadeColor, borderColor;
        cardColor = config.colors.white;
        headingColor = config.colors.headingColor;
        axisColor = config.colors.axisColor;
        borderColor = config.colors.borderColor;
        const totalRevenueChartEl = document.querySelector('#totalRevenueChart'),
          totalRevenueChartOptions = {
            series: [
              {
                name: '<?php echo date("Y"); ?>',
                data: [<?php echo getDataByMonth(); ?>]
              }
            ],
            chart: { height: 300, stacked: true, type: 'bar', toolbar: { show: false } },
            plotOptions: {
              bar: { horizontal: false, columnWidth: '15%', borderRadius: 2, startingShape: 'rounded', endingShape: 'rounded' }
            },
            colors: [config.colors.primary, config.colors.info],
            dataLabels: { enabled: false },
            stroke: { curve: 'smooth', width: 0, lineCap: 'round', colors: [cardColor]
            },
            legend: {
              show: true,
              horizontalAlign: 'left',
              position: 'top',
              markers: { height: 8, width: 8, radius: 12, offsetX: -3 },
              labels: { colors: axisColor },
              itemMargin: { horizontal: 10 }
            },
            grid: {
              borderColor: borderColor, padding: { top: 0, bottom: -8, left: 20, right: 20 }
            },
            xaxis: {
              categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
              labels: { style: { fontSize: '11px', colors: axisColor } },
              axisTicks: { show: false },
              axisBorder: { show: false }
            },
            yaxis: {
              labels: { style: { fontSize: '13px', colors: axisColor } }
            },
            states: {
              hover: { filter: { type: 'none' } },
              active: { filter: { type: 'none' } }
            }
          };
        if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
          const totalRevenueChart = new ApexCharts(totalRevenueChartEl, totalRevenueChartOptions);
          totalRevenueChart.render();
        }
      };
    </script>
  </foot>
</html>

<?php
  function getDataByMonth() {
    global $CON;
    $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    foreach($month as $i => $m) {
      $i += 1;
      $year = date("Y");
      $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(payment_amount) AS `total` FROM `tbl_invoice_payment` WHERE MONTH(timestamp) = '$i' AND YEAR(timestamp) = '$year'"));
      $total = ($ROW['total'] == '') ? 0 : $ROW['total'];
      $data_by_month .= str_replace(',', '', number_format($total, 2)).', ';
    }
    return rtrim($data_by_month, ', ');
  }
?>
