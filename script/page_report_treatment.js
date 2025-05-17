
  $(document).ready(function() {
    var start = moment().startOf('month');
    var end = moment().endOf('month');
    cb(start, end);
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
    getDataTable();
  });

  $(document).on('click', '#btnFilter', function() {
    getDataTable();
  });

  $('#sid').change(function() {
    getDataTable();
  });

  $('#zid').change(function() {
    getDataTable();
  });

  function cb(start, end) {
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
  }

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
          { extend: 'print', text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: {columns:[0,1,2,3,4]} },
          { extend: 'excelHtml5', text: '<i class="bx bx-file me-2"></i>Excel', className: "dropdown-item", exportOptions: {columns:[0,1,2,3,4]} },
          { extend: 'copyHtml5', text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: {columns:[0,1,2,3,4]} },
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
        url: '../data/query_report_treatment.php',
        type: 'POST',
        data : {
          'qid' : 1,
          'date' : $('#date').val(),
          'sid' : $('#sid').val(),
        }
      },
      aoColumnDefs: [
        { bSortable: false, aTargets: [] },
        { bVisible: false, aTargets: [] },
      ],
      drawCallback : function(settings) {
        $('#grandTotal').html(settings.json.grandTotal);
      }
    });
  }