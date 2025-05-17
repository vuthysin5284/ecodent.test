<?php
  $page = 'Dashboard'; 
	include_once('../inc/session.php');
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
        $page_title = 'Dentist';
        $text_dropdown = 'Show More';
        $graph_title = 'Income Report';
        $th1 = 'Invoice No';
        $th2 = 'Image';
        $th3 = 'Patient';
        $th4 = 'Invoice';
        $th5 = 'Total ($)';
        $th6 = 'Share ($)';
        $th7 = 'Status';
        $th8 = 'Dentist';
        $th9 = 'Timestamp';
      } else {
        $page_category = 'ផ្ទាំងបង្ហាញ';
        $page_title = 'ទន្តបណ្ឌិត';
        $text_dropdown = 'ពត៌មានបន្ថែម';
        $graph_title = 'របាយការណ៍ចំណូល';
        $th1 = 'លេខវិក្កយបត្រ';
        $th2 = 'រូបភាព';
        $th3 = 'អតិថិជន';
        $th4 = 'វិក្កយបត្រ';
        $th5 = 'សរុប ($)';
        $th6 = 'ទទួលបាន ($)';
        $th7 = 'សំគាល់';
        $th8 = 'ទន្តបណ្ឌិត';
        $th9 = 'កាលបរិច្ឆេទ';
      }
      include_once('../inc/config.php');
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
                <div class="card-body p-3">
                  <div class="table-responsive p-2">
                    <table class="table table-bordered pt-2" id="dataTable">
                      <thead class="text-nowrap">
                        <tr>
                          <th class="text-center"><?php echo $th1; ?></th>
                          <th class="text-center"><?php echo $th2; ?></th>
                          <th class="text-left"><?php echo $th3; ?></th>
                          <th class="text-left"><?php echo $th4; ?></th>
                          <th class="text-center"><?php echo $th5; ?></th>
                          <th class="text-center"><?php echo $th6; ?></th>
                          <th class="text-center"><?php echo $th7; ?></th>
                          <th class="text-center"><?php echo $th8; ?></th>
                          <th class="text-center"><?php echo $th9; ?></th>
                        </tr>
                      </thead>
                      <tbody class="text-nowrap"></tbody>
                      <tfoot>
                        <tr>
                          <td colspan="4"></td>
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
            <!-- Footer -->
            <footer class="content-footer footer bg-footer-theme">
              <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                <div class="mb-2 mb-md-0">
                  © <script> document.write(new Date().getFullYear()); </script> , made with ❤️ by
                  <a href="javascript:void(0);" class="footer-link fw-bolder">DevTeam</a>
                </div>
              </div>
            </footer>
            <div class="content-backdrop fade"></div>
          </div>
        </div>
      </div>
    </div>
    <?php include_once('../inc/footer.php'); ?> 
    <script type="text/javascript">
      /* Function getDataReport */
      function getDataReport(str) {
        $.ajax({
          url : '../data/query_dashboard_dentist.php',
          type : 'POST',
          data : {
            qid : 2,
            uid : '<?php echo $log; ?>',
            date : $('#date').val(),
            str : $('#str').val(),
            lang : '<?php echo $lang; ?>',
          },
          success : function(data) {
            $('#dataReport').html(data);
          }
        });
      }
      /* Function getDateRange */
      function cb(start, end, str) {
        $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
        $('#str').val(str);
      }
      /* Main Function */
      $(document).ready(function() {
        var start = moment().startOf('month');
        var end = moment().endOf('month');
        var str = 'This Month';
        cb(start, end, str);
        $('#name').select2({ theme: "bootstrap-5", dropdownParent: "#Modal", placeholder: $(this).data( 'placeholder'), closeOnSelect: true });
        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            title : str,
            ranges: {
              'Today': [moment(), moment(), 'Today'],
              'This Week': [moment().startOf('week'), moment().endOf('week'), 'This Week'],
              'This Month': [moment().startOf('month'), moment().endOf('month'), 'This Month'],
              'This Year': [moment().startOf('year'), moment().endOf('year'), 'This Year'],
            }
        }, cb);
        getDataReport('This Month');
        getDataTable();
      });
      /* Function getTable */
      function getDataTable() {
        $('#dataTable').DataTable({  
          "fnCreatedRow": function(nRow, aData, iDataIndex) {
            $(nRow).attr('id', aData[0]);
          },  
          dom: '<"d-flex justify-content-between"<"d-flex justify-content-start justify-content-md-end align-items-baseline mb-0"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-0"lB>><""f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
          language : {
            sLengthMenu : "_MENU_",
            search : "",
            searchPlaceholder : "Search",
          },
          buttons: [{
            extend: 'collection',
            className: 'dropdown-toggle btn btn-label-secondary shadow-none ms-2',
            text: '<i class="bx bx-export me-2"></i>Export',
            buttons: [
              { extend: 'print', text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7,8]} },
              { extend: 'excelHtml5', text: '<i class="bx bx-file me-2"></i>Excel', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7,8]} },
              { extend: 'copyHtml5', text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7,8]} },
              { extend: 'colvis', text: '<i class="bx bx-grid me-2"></i>Column', className: "dropdown-item" },
            ]
          }],
          pageLength: 25,
          serverSide: true,
          processing: true,
          paging: true,
          bDestroy : true,
          order: [],
          ajax: {
            url: '../data/query_dashboard_dentist.php',
            type: 'POST',
            data : {
              'qid' : 1,
              'date' : $('#date').val(),
              'uid' : '<?php echo $log; ?>'
            },
          },
          aoColumnDefs: [
            { bSortable: false, aTargets: [1,5] },
            { bVisible: false, aTargets: [] },
          ],
          drawCallback : function(settings) {
            $('#grandTotal').html(settings.json.grandTotal);
            $('#shareTotal').html(settings.json.shareTotal);
          }
        });
      }
      /* Select Dentist */
      $('#sid').change(function() {
        getDataTable();
      });
      /* Select Status */
      $('#zid').change(function() {
        getDataTable();
      });
      /* Click Filter Button */
      $(document).on('click', '#btnFilter', function() {
        getDataReport();
        getDataTable();
      });
    </script>
    <script type="text/javascript">
      window.onload = function(){
        var activeMenu = document.getElementById("Dashboard");
        activeMenu.classList.add("open");
        activeMenu.classList.add("active");
        var activeSubMenu = document.getElementById("Dashboards");
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
  </body>
</html>
<?php
  function getDataByMonth() {
    global $CON;
    $uid = $_SESSION['uid'];
    $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_salary` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $month = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec');
    foreach($month as $i => $m) {
      $i += 1;
      $year = date("Y");
      $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT SUM(share_amount) AS `share` FROM `tbl_invoice_patient` WHERE MONTH(timestamp) = '$i' AND YEAR(timestamp) = '$year' AND `staff_id` = '$uid'"));
      $total = ($ROW['share'] == '') ? 0 : number_format($ROW['share'], 2, '.', '') + $USER['staff_salary'];
      $data_by_month .= $total.', ';
    }
    return rtrim($data_by_month, ', ');
  }
?>
