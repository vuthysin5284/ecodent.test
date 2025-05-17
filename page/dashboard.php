<?php
  $page = 'Dashboard';
  session_start();
  $log = $_SESSION['uid'];
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
        $page_category = 'Dashboard';
        $page_title = 'Administrator';
        $text_dropdown = 'Show More';
        $graph_title = 'Total Revenue';
        $transaction_title = 'Transaction';
        $statistic_title = 'Treatment Statistics';
        $stock_title = 'Stock Alert';
      } else {
        $page_category = 'ផ្ទាំងពត៌មាន';
        $page_title = 'អ្នកគ្រប់គ្រង';
        $text_dropdown = 'ពត៌មានបន្ថែម';
        $graph_title = 'ចំណូលសរុប';
        $transaction_title = 'ប្រតិបត្តិការប្រាក់';
        $statistic_title = 'ស្ថិតិនៃការព្យាបាល';
        $stock_title = 'សម្ភារៈជិតអស់ស្តុក';
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
              <!-- Heading -->
              <div class="row justify-content-between">
                <div class="me-auto col-lg-9 col-md-7 col-sm-12 col-12">
                  <h4 class="fw-bold py-2 mb-2">
                    <span class="text-muted fw-light"><?php echo $page_category; ?> </span> 
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
                <!-- Data Report -->
                <div class="col-lg-4 col-md-12">
                  <div class="row" id="dataReport"></div>
                </div>
                <!-- Graph Report -->
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
              <div class="row">
                <!-- Transactions -->
                <div class="col-md-6 col-lg-4 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="card-title m-0 me-2"><?php echo $transaction_title; ?></h5>
                      <div class="dropdown">
			                  <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
			                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6"><a class="dropdown-item" href="report_transaction.php"><?php echo $text_dropdown; ?></a></div>
			                </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0" id="dataTransaction"></ul>
                    </div>
                  </div>
                </div>
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-4 col-xl-4 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                      <div class="card-title mb-0"><h5 class="m-0 me-2"><?php echo $statistic_title; ?></h5></div>
                      <div class="dropdown">
			                  <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
			                  <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6"><a class="dropdown-item" href="report_treatment.php"><?php echo $text_dropdown; ?></a></div>
			                </div>
                    </div>
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-left gap-1">
                          <h2 class="mb-2"><small>$ </small><?php echo getTotalTreatment(); ?></h2>
                          <span>Total Treatment</span>
                        </div>
                        <div id="orderStatisticsChart"></div>
                      </div>
                      <ul class="p-0 m-0" id="treatmentStatisctics">
                      </ul>
                    </div>
                  </div>
                </div>
                <div class="col-md-6 col-lg-4 order-2 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="card-title m-0 me-2"><?php echo $stock_title; ?></h5>
                      <div class="dropdown">
                        <button class="btn p-0" type="button" id="cardOpt6" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="bx bx-dots-vertical-rounded"></i></button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6"><a class="dropdown-item" href="../page/stock_product_list.php?pgid=14"><?php echo $text_dropdown; ?></a></div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0" id="dataStockAlert"></ul>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <?php include_once('../inc/footage.php'); ?> 
          </div>
        </div>
      </div>
    </div>
    <?php include_once('../inc/footer.php'); ?> 
    <script src="../script/page_dashboard.js"></script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Dashboard");
        activeMenu.classList.add("active");
        activeMenu.classList.add("open");
        var activeSubMenu = document.getElementById("Dashboards");
        activeSubMenu.classList.add("active");
        /* ApexChart */
        let cardColor, headingColor, axisColor, shadeColor, borderColor;
        cardColor = config.colors.white;
        headingColor = config.colors.headingColor;
        axisColor = config.colors.axisColor;
        borderColor = config.colors.borderColor;
        const totalRevenueChartEl = document.querySelector('#totalRevenueChart'),
          totalRevenueChartOptions = {
            series: [
              {
                name: '<?php echo date('Y'); ?>',
                data : [<?php echo getDataByMonth(); ?>]
                
              },
            ],
            chart: { height: 300, stacked: true, type: 'bar', toolbar: { show: false } },
            plotOptions: {
              bar: { horizontal: false, columnWidth: '15%', borderRadius: 2, startingShape: 'rounded', endingShape: 'rounded' }
            },
            colors: [config.colors.primary, config.colors.danger],
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
        const chartOrderStatistics = document.querySelector('#orderStatisticsChart'),
          orderChartConfig = {
            chart: { height: 165, width: 130, type: 'donut' },
            labels: [<?php echo getTreatmentCategory(); ?>],
            series: [<?php echo getStatistic(); ?>],
            colors: [config.colors.success, config.colors.primary, config.colors.info, config.colors.warning],
            stroke: { width: 5, colors: cardColor },
            dataLabels: { enabled: false, formatter: function (val, opt) { return parseInt(val) + '%'; } },
            legend: { show: false },
            grid: { padding: { top: 0, bottom: 0, right: 15 } },
            plotOptions: {
              pie: {
                donut: { size: '75%', labels: { show: true,
                  value: { fontSize: '1.5rem', fontFamily: 'Public Sans', color: headingColor, offsetY: -15,
                    formatter: function (val) { return parseInt(val) + '%'; }
                  },
                  name: { offsetY: 20, fontFamily: 'Public Sans' },
                  total: { show: true, fontSize: '0.8125rem', color: axisColor, label: 'Total',
                    formatter: function (w) { return '100%'; }
                  }
                } }
              }
            }
          };
        if (typeof chartOrderStatistics !== undefined && chartOrderStatistics !== null) {
          const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
          statisticsChart.render();
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
      $year = date("Y");
      $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(`payment_amount`) AS `total` FROM `tbl_invoice_payment` WHERE MONTH(timestamp) = '$i' AND YEAR(timestamp) = '$year'"));
      $incomes = (float) $ROW['total'];
      $data_by_month .= str_replace(',', '', number_format($incomes, 0)).', ';
    }
    return rtrim($data_by_month, ', ');
  }

  function getStatistic() {
    global $CON;
    $SQL = "SELECT SUM(`tooth_qty`) AS `amount` FROM `tbl_invoice_treatment` AS `t` INNER JOIN `tbl_treatment_service` AS `s` ON (`t`.`tmsv_id` = `s`.`id`) INNER JOIN `tbl_invoice_patient` AS `i` ON (`t`.`inv_id` = `i`.`id`) INNER JOIN `tbl_product_category` AS `c` ON (`s`.`service_cate_id` = `c`.`id`) INNER JOIN `tbl_customer` AS `p` ON (`i`.`cust_id` = `p`.`id`) WHERE `cust_status` = 1 AND `inv_status` > 1";
    $ROWs = mysqli_fetch_assoc(mysqli_query($CON, $SQL));
    $SQL .= " GROUP BY `service_cate_id` ORDER BY `amount` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      if($i < 4) {
        $amount = (float) $ROW['amount'];
        $total = (float) $ROWs['amount'];
        $ts = ($amount * 100) / $total;
        $str .= number_format($ts, 2).', ';
      }
      $i = $i + 1;
    }
    return rtrim($str, ', ');
  }
  function getTotalTreatment() {
    global $CON;
    $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(`payment_amount`) as `total` FROM `tbl_invoice_payment`"));
    return number_format($ROW['total'], 0);
  }

  function getTreatmentCategory() {
    global $CON;
    $QUERY = mysqli_query($CON, "SELECT SUM(`tooth_qty`) AS `total` FROM `tbl_invoice_treatment` AS `t` INNER JOIN `tbl_treatment_service` AS `s` ON (`t`.`tmsv_id` = `s`.`id`) INNER JOIN `tbl_invoice_patient` AS `i` ON (`t`.`inv_id` = `i`.`id`) INNER JOIN `tbl_product_category` AS `c` ON (`s`.`service_cate_id` = `c`.`id`) INNER JOIN `tbl_customer` AS `p` ON (`i`.`cust_id` = `p`.`id`) WHERE `cust_status` = 1 AND `inv_status` > 1 GROUP BY `service_cate_id` ORDER BY `total` DESC");
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      if( $i < 4) { $tg .= "'".$ROW['total']."', "; }
      $i = $i + 1;
    }
    return rtrim($tg, ', ');
  }
?>
