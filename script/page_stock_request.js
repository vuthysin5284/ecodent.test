
  $(document).ready(function() {
    var start = moment();
    var end = moment();
    cb(start, end, str);
    $("#qty").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
    $('#product').select2({ theme: "bootstrap-5", dropdownParent: "#Modal", placeholder: $(this).data( 'placeholder'), closeOnSelect: true });
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

  $(document).on('click', '#create', function() {
    clearForm();
    $('.modal-dialog form').attr('id','addForm');
    $.ajax({
      url : "../data/query_stock_request.php",
      data : { qid : 5 },
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        var id = parseInt(json.id) + 1;
        $('#id').val(id);
      }
    }); 
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 3);
    formData.append('uid', uid);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_stock_request.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'True') {              
          $('#Modal').modal('hide');
          $('#dataTable').DataTable().draw();
          alertText('Product request has been saved to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });
  
  $('#dataTable').on('click', '.editBtn', function() {   
    var table = $('#dataTable').DataTable();
    var trid = $(this).closest('tr').attr('id');
    var id = $(this).data('id');
    clearForm();
    $('#Modal').modal('show');
    $('.modal-dialog form').attr('id','editForm');
    $.ajax({
      url : "../data/query_stock_request.php",
      data : {qid : 2, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        $('#product').val(json.prod_id).trigger('change');
        $('#qty').val(json.request_qty);
      }
    });
  });
  
  $(document).on('click', '#btnFilter', function() {
    getDataTable();
  });
  
  $(document).on('submit', '#editForm', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('qid', 4);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_stock_request.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'True') {
          clearForm();
          $('#Modal').modal('hide');
          $('#dataTable').DataTable().draw();
          alertText('Product request has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });
  /* Click Approve Button */
  $(document).on('click', '.showBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to approve this request? ")) {
      $.ajax({
        url: "../data/query_stock_request.php",
        data: { qid : 6, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          status = json.status;
          if (status == 'True') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Request has been approved and saved to database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });
  /* Click Delete Button */
  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this product from request? ")) {
      $.ajax({
        url: "../data/query_stock_request.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'True') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Product has been deleted from stock request!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });
  function cb(start, end) {
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
  }

  function clearForm() {
    $('#product').val(null).trigger('change');
    $('#qty').val('');
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
      paging : false,
      serverSide: true,
      processing: true,
      paging: false,
      searching : false,
      bDestroy : true,
      order: [],
      ajax: {
        url: '../data/query_stock_request.php',
        type: 'POST',
        data : {
          "qid" : 1,
          "date" : $('#date').val(),
          "uid" : $('#navLog').val(), 
          pid : $('#pid').val()
        }
      },
      "aoColumnDefs": [{
          "bSortable": false,
          "aTargets": [0,6],
        }]
    });
  }