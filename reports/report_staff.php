<?php
  $page = 'Staff Report'; 
  // include_once('../inc/session.php');
  session_start();
  $log = $_SESSION['uid'];
  $clinic_id = $_SESSION['business_id'];
  $lang = 1;
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
        $page_category = 'Report';
        $page_title = 'Staff';
        $text_dropdown = 'Show More';
        $graph_title = 'Staff Report';
        $th1 = 'Staff ID';
        $th2 = 'Image';
        $th3 = 'Staff';
        $th4 = 'Sex';
        $th5 = 'Age';
        $th6 = 'Contact';
        $th7 = 'Address';
        $th8 = 'Income ($)';
        $th9 = 'Expense ($)';
        $th10 = 'Action';
      } else {
        $page_category = 'របាយការណ៍';
        $page_title = 'បុគ្គលិក';
        $text_dropdown = 'ពត៌មានបន្ថែម';
        $graph_title = 'របាយការណ៍បុគ្គលិក';
        $th1 = 'លេខកូដ';
        $th2 = 'រូបភាព';
        $th3 = 'បុគ្គលិក';
        $th4 = 'ភេទ';
        $th5 = 'អាយុ';
        $th6 = 'លេខទូរស័ព្ទ';
        $th7 = 'អាស័យដ្ឋាន';
        $th8 = 'ចំណូល ($)';
        $th9 = 'ចំណាយ ($)';
        $th10 = 'ដំណើរការ';
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
              <!-- DataTable -->
              <div class="card">
                <div class="table-responsive text-nowrap p-4 me-md-0 me-sm-1 me-4">
                  <table class="table table-bordered pt-2" id="dataTable">
                    <thead>
                      <tr>
                        <th class="text-center"><?php echo $th1; ?></th>
                        <th class="text-center"><?php echo $th2; ?></th>
                        <th class="text-left"><?php echo $th3; ?></th>
                        <th class="text-center"><?php echo $th4; ?></th>
                        <th class="text-center"><?php echo $th5; ?></th>
                        <th class="text-center"><?php echo $th6; ?></th>
                        <th class="text-center"><?php echo $th7; ?></th>
                        <th class="text-center"><?php echo $th8; ?></th>
                        <th class="text-center"><?php echo $th9; ?></th>
                        <th class="text-center"><?php echo $th10; ?></th>
                      </tr>
                    </thead>
                    <tbody class="text-nowrap"></tbody>
                  </table>
                </div>
              </div>
            </div>
            <?php include_once('../inc/footage.php'); ?> 
          </div>
        </div>
      </div>
    </div>
    <?php include_once('../inc/footer.php'); ?> 
    <script src="../script/page_report_staff.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Analytics");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("sub-Staff Report");
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
  </body>
</html>
<?php
  function getDataByMonth() {
    global $CON;
    $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    foreach($month as $i => $m) {
      $i += 1;
      $MONTH = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS '$m' FROM `tbl_staff` WHERE MONTH(timestamp) = '$i' AND `staff_status` = 1"));
      $data_by_month .= $MONTH[$m].', ';
    }
    return rtrim($data_by_month, ', ');
  }
?>
