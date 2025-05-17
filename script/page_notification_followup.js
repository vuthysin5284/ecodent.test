
  $(document).ready(function() {
    getDateFilter();
    getDataTable();
  });

  $('#dataTable').on('click', '.editBtn', function() {   
    var table = $('#dataTable').DataTable();
    var trid = $(this).closest('tr').attr('id');
    var id = $(this).data('id');
    $('#Modal').modal('show');
    $('.modal-dialog form').attr('id','editForm');
    $.ajax({
      url : "../data/query_notification_appointment.php",
      data : {qid : 3, id : id, 
        pid : $('#pid').val()},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        var cid = 'P-' + padZero(json.cust_id, 5);
        var img = json.cust_image;
        var folder = '';
        if (img != '0') { folder = cid + '/'; }
        $('#id').val(id);
        $('#cid').val(cid);
        $('#code').val(json.cust_code);
        $('#name').val(json.cust_fname);
        $('#datetime').val(json.appo_datetime);
        $('#duration').val(json.appo_duration);
        $('#note').val(json.appo_note);
        $('#sid').val(json.staff_id);
        $('#uploadedAvatar').attr('src','../images/profiles/' + folder + img + '.jpg');
      }
    });
  });

  $(document).on('submit', '#editForm', function (e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 5);
    formData.append('uid', uid);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_notification_appointment.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'true') {
          $('#Modal').modal('hide');
          $('#dataTable').DataTable().draw();
          alertText('Appointment has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this appointment? ")) {
      $.ajax({
        url: "../data/query_notification_appointment.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Appointment has been deleted from database!', 'primary');
          } else {
            alert(data);
            return;
          }
        }
      });
    } else { return null; }
  });

  $(document).on('click', '.showBtn', function() {
    var id = $(this).data('id');
    if (confirm("Do you want to move this patient to appointment?")) {
      $.ajax({
        url: "../data/query_notification_follow_up.php",
        data: { qid : 2, id: id },
        type: 'POST',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Appointment has been med to appointment!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });
  
  $(document).on('click', '#btnFilter', function() {
    getDataTable();
  });

  function cb(start, end) {
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
  }

  function getDateFilter() {
    var start = moment();
    var end = moment();
    cb(start, end);
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'Tomorow': [moment().add(1, 'days'), moment().add(1, 'days')],
          'This Week': [moment().startOf('week'), moment().endOf('week')],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'This Year': [moment().startOf('year'), moment().endOf('year')],
        }
    }, cb);
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
      order: [],
      bDestroy : true,
      ajax: {
        url: '../data/query_notification_follow_up.php',
        type: 'POST',
        data : {
          qid : 1,
          date : $('#date').val(),
          uid : $('#navLog').val(), 
          pid : $('#pid').val()
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,8],
        }]
    });
  }