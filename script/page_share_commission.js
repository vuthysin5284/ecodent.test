
  $(document).ready(function() {
    getDateFilter();
    getDataTable();
  });

  $(document).on('click', '#btnFilter', function() {
    getDataTable();
  });

  $(document).on('click', '.shareBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    var share = $(this).data('share');
    var status = $(this).data('status');
    if (confirm("Are you sure that you want to share commission on this invoice? ")) {
      $.ajax({
        url: "../data/query_invoice_commission.php",
        data: { qid : 2, id : id, share : share, status : status},
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'True') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Invoice\'s commission has been shared to dentist!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to withdraw commission on this invoice? ")) {
      $.ajax({
        url: "../data/query_invoice_commission.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'True') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Invoice\'s commission has been withdrawed from dentist!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  function getDataTable() {
    $('#dataTable').DataTable({  
      fnCreatedRow: function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },
      dom: '<"d-flex justify-content-between"<""l><""f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      language : {
        sLengthMenu : "_MENU_",
        search : "",
        searchPlaceholder : "Search",
      },
      pageLength: 25,
      serverSide: true,
      processing: true,
      paging: true,
      bDestroy : true,
      order: [],
      ajax: {
        url: '../data/query_invoice_commission.php',
        type: 'POST',
        data : {
          'qid' : 1,
          'date' : $('#date').val(),
          'sid' : $('#sid').val(),
          'zid' : $('#zid').val(),
          'uid' : $('#navLog').val()
        },
      },
      aoColumnDefs: [{ 
        bSortable: false,
        aTargets: [1,5,8] 
      }],
      drawCallback : function(settings) {
        $('#grandTotal').html(settings.json.grandTotal);
        $('#shareTotal').html(settings.json.shareTotal);
        $('[data-bs-toggle="tooltip"]').tooltip();
      }
    });
  }

  $('#sid').change(function() {
    getDataTable();
  });

  $('#zid').change(function() {
    getDataTable();
  });

  function cb(start, end) {
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
  }

  function getDateFilter() {
    var start = moment().startOf('month');
    var end = moment().endOf('month');
    cb(start, end);
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'This Week': [moment().startOf('week'), moment().endOf('week')],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'This Year': [moment().startOf('year'), moment().endOf('year')],
        }
    }, cb);
  }