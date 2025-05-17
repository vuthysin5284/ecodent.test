<?php
  $page = 'Staff Commission'; 
  session_start();
	$log = $_SESSION['uid'];
  $lang = $_SESSION['lang'];
  if (!isset($log)) { header('Location: login.php'); }
  error_reporting(0);
  $sid = $_GET['sid'];
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
        $page_category = 'Report';
        $page_title = 'Commission';
        $text_dropdown = 'Show More';
        $graph_title = 'Income Report';
        $th1 = 'Invoice No';
        $th2 = 'Image';
        $th3 = 'Patient';
        $th4 = 'Total ($)';
        $th5 = 'Share ($)';
        $th6 = 'Status';
        $th7 = 'Dentist';
        $th8 = 'Timestamp';
      } else {
        $page_category = 'របាយការណ៍';
        $page_title = 'បែងចែកភាគលាភ';
        $text_dropdown = 'ពត៌មានបន្ថែម';
        $graph_title = 'របាយការណ៍ចំណូល';
        $th1 = 'លេខវិក្កយបត្រ';
        $th2 = 'រូបភាព';
        $th3 = 'អតិថិជន';
        $th4 = 'សរុប ($)';
        $th5 = 'ទទួលបាន ($)';
        $th6 = 'សំគាល់';
        $th7 = 'ទន្តបណ្ឌិត';
        $th8 = 'កាលបរិច្ឆេទ';
      }
      include_once('../inc/config.php');
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
    <input type="hidden" id="sid" name="sid" value="<?php echo $sid; ?>" class="form-control" readonly/>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="row justify-content-between">
                <div class="me-auto col-lg-9 col-md-7 col-sm-12 col-12">
                  <h4 class="fw-bold py-2 mb-2">
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
              <div class="row">
                <div class="col-lg-4 col-md-12">
                  <div class="row" id="dataReport"></div>
                </div>
                <div class="col-12 col-lg-8 mb-4">
                  <div class="card h-100">
                    <div class="row row-bordered g-0">
                      <div class="col-md-12">
                        <h5 class="card-header m-0 me-2 pb-3"><?php echo $graph_title; ?></h5>
                        <div id="totalRevenueChart" class="px-2"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="card">
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center"><?php echo $th1; ?></th>
                          <th class="text-center"><?php echo $th2; ?></th>
                          <th class="text-left"><?php echo $th3; ?></th>
                          <th class="text-center"><?php echo $th4; ?></th>
                          <th class="text-center"><?php echo $th5; ?></th>
                          <th class="text-center"><?php echo $th6; ?></th>
                          <th class="text-center"><?php echo $th7; ?></th>
                          <th class="text-center"><?php echo $th8; ?></th>
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                      <tfoot>
                        <tr>
                          <td colspan="3"></td>
                          <td class="fw-bolder" id="grandTotal"></td>
                          <td class="fw-bolder" id="shareTotal"></td>
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
    <script src="../script/page_report_staff_commission.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Report");
        activeMenu.classList.add("active");

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
    $uid = $_GET['sid'];
    $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_salary` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    foreach($month as $i => $m) {
      $i += 1;
      $year = date("Y");
      $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(share_amount) AS `share` FROM `tbl_invoice_patient` WHERE MONTH(timestamp) = '$i' AND YEAR(timestamp) = '$year' AND `staff_id` = '$uid'"));
      $total = ($ROW['share'] == '') ? 0 : number_format($ROW['share'], 2, '.', '') + $USER['staff_salary'];
      $data_by_month .= number_format($total, 0).', ';
    }
    return rtrim($data_by_month, ', ');
  }
?>
