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
    getDataTable();
  });

  $(document).on('click', '#btnFilter', function() { 
    getDataTable();
  });

  function cb(start, end, str) {
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
    $('#str').val(str);
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
        className: 'btn btn-primary ms-2',
        text: 'All',
        action: function (e, dt, node, config) { _status = 'ALL'; _table.ajax.reload(); }
      },{
        className: 'btn btn-primary',
        text: 'Doctor',
        action: function (e, dt, node, config) { _status = '1'; _table.ajax.reload(); }
      },{
        className: 'btn btn-primary',
        text: 'Patient',
        action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
      },{
        className: 'btn btn-primary',
        text: 'Invoice',
        action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
      },{
        extend: 'collection',
        className: 'dropdown-toggle btn btn-label-secondary shadow-none',
        text: '<i class="bx bx-export"></i>Export',
        buttons: [
          { extend: 'print', text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
          { extend: 'excelHtml5', text: '<i class="bx bx-file me-2"></i>Excel', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
          { extend: 'copyHtml5', text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
          { extend: 'colvis', text: '<i class="bx bx-grid me-2"></i>Column', className: "dropdown-item" },
        ]
      }],
      pageLength : 25,
      serverSide : true,
      processing : true,
      searching : false,
      paging : true,
      bDestroy : true,
      order: [],
      ajax: { 
        url: '../data/query_report_pending_invoice.php',
        type: 'POST',
        data : {
          qid : 1,
          date : $('#date').val(),
          uid : $('#navLog').val(), 
          pid : $('#pid').val()
        }
      },
      aoColumnDefs : [{
          bSortable : false,
          aTargets : [0,1,2,3,4,5,6],
        }],
        
      drawCallback : function(settings) { 
        $('#remainTotal').html(settings.json.remainTotal); 
      }
    });
  }
 