
  $(document).ready(function() {
    var start = moment();
    var end = moment();
    cb(start, end);
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        title : str,
        ranges: {
          'Today': [moment(), moment()],
          'This Week': [moment().startOf('week'), moment().endOf('week')],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'This Year': [moment().startOf('year'), moment().endOf('year')],
        }
    }, cb);
    getDataTable();
  });
  
  $(document).on('click', '#btnFilter', function() {
    getDataTable();
  });
  
  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this invoice? ")) {
      $.ajax({
        url: "../data/query_patient_invoice.php",
        data: { qid: 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Invoice has been deleted from database!', 'primary');
          } else {
            alert(data);
            return;
          }
        }
      });
    } else { return null; }
  });
  
  $(document).on('click', '.editBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to move this invoice to pending? ")) {
      $.ajax({
        url: "../data/query_invoice_draft.php",
        data: { qid: 2, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Invoice has been moved to pending!', 'primary');
          } else {
            alert(data);
            return;
          }
        }
      });
    } else { return null; }
  });

  function cb(start, end) {
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));

  }
  
  function getDataTable() {
    $('#dataTable').DataTable({  
      fnCreatedRow: function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },
      drawCallback: function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
      },
      pageLength : 25,
      serverSide : true,
      processing : true,
      searching : false,
      paging : false,
      bDestroy : true,
      order: [],
      ajax: { 
        url: '../data/query_invoice_draft.php',
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
          aTargets : [0,7],
        }]
    });
  }