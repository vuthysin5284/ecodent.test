
  $(document).ready(function() {
    var start = moment();
    var end = moment();
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
    getDataTable();        
  });
  
  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to check-out this patient?")) {
      $.ajax({
        url: '../data/query_notification_serving.php',
        data: { qid : 0, id: id },
        type: 'POST',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Patient has been check out from serving!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });
  
  $(document).on('submit', '#addForm', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('qid', 0);
    $.ajax({
      type : 'POST',
      enctype : 'multipart/form-data',
      url : '../data/query_notification_serving.php',
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'success') {
          clearForm();
          $('#Modal').modal('hide');
          $('#dataTable').DataTable().draw();
          alertText('Patient has been check out from serving!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });
  
  $(document).on('click', '#btnFilter', function() {
    getDataTable();
  });
  
  function clearForm() {
    $('#id').val('');
    $('#date').val('');
    $('#note').val('');
  }
  
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
      pageLength: 25,
      serverSide: true,
      processing: true,
      searching : false,
      paging: false,
      bDestroy : true,
      order: [],
      ajax: {
        url: '../data/query_notification_serving.php',
        type: 'POST',
        data : {
          qid : 1,
          date : $('#date').val(), 
          pid : $('#pid').val()
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,7],
        }]
    });
  }