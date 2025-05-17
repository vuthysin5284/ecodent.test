<?php
  $page = 'Expense Report';
  // include_once('../inc/session.php');
  session_start();
  $log = $_SESSION['uid'];
  $clinic_id = $_SESSION['business_id'];
  $lang = 1;
  include_once ('../inc/config.php');
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
        $page_title = 'Expense report';
        $text_dropdown = 'Show More';
        $graph_title = 'Expense report';
        $th2 = 'Expense Description';
        $th3 = 'Category';
        $th4 = 'Supplier';
        $th5 = 'Total';
        $th6 = 'Remain';
        $th7 = 'Created By';
      } else {
        $page_category = 'របាយការណ៍';
        $page_title = 'ចំណាយ';
        $text_dropdown = 'ពត៌មានបន្ថែម';
        $graph_title = 'របាយការណ៍ចំណាយ';
        $th2 = 'ការចំណាយ';
        $th3 = 'ប្រភេទ';
        $th4 = 'អ្នកផ្គត់ផ្គង់';
        $th5 = 'ទឹកប្រាក់សរុប';
        $th6 = 'ទឹកប្រាក់នៅខ្វះ';
        $th7 = 'បង្កើតដោយ';
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
                  <button type="button" class="btn btn-icon btn-primary mx-2" id="btnFilter"><i class="bx bx-filter-alt"></i></button>
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
                          <th class="text-center">#</th>
                          <th class="text-left"><?php echo $th2; ?></th>
                          <th class="text-center"><?php echo $th3; ?></th>
                          <th class="text-left"><?php echo $th4; ?></th>
                          <th class="text-center"><?php echo $th5; ?></th>
                          <th class="text-center"><?php echo $th6; ?></th>
                          <th class="text-center"><?php echo $th7; ?></th>
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
    <script src="../script/page_report_expense.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Analytics");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-Expense Report");
        activeSubMenu.classList.add("active");

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
              labels: { style: { fontSize: '12px', colors: axisColor } },
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
      $expenses = 0;
      $year = date("Y");
      $EXP_QUERY = mysqli_query($CON, "SELECT `exp_payment_amount` FROM `tbl_expense_payment` WHERE MONTH(timestamp) = '$i' AND YEAR(timestamp) = '$year'");
      while ($MONTH = mysqli_fetch_assoc($EXP_QUERY)) {
        $expenses = $expenses + (float) $MONTH['exp_payment_amount'];
      }
      $data_by_month .= $expenses.', ';
    }
    return rtrim($data_by_month, ', ');
  }
?>
