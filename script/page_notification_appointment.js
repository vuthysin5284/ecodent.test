
  $(document).ready(function() {
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
    getDataTable();
    $('#name').select2({
      ajax : {
        url : '../data/query_select_patient.php',
        dataType : 'json',
        data : function (params) {
          var query = {
            search : params.term,
            type : 'cust_search',
          };
          return query;
        },
        processResults : function (data) {
          return {
            results : data
          }
        }
      },
      cache : false,
      theme: "bootstrap-5",
      dropdownParent: "#Modal",
      closeOnSelect: true,
      placeholder: '--- select patient ---',
    });
  });

  $(document).on('click', '#btnFilter', function() {
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    clearForm();
    $('.modal-dialog form').attr('id','addForm');
    $('#name').next(".select2-container").show();
    $('#name').prop('required', true);
    $('#showName').hide();
  });

  $(document).on('change', '#name', function(){
    var name = $('#name').val();
    $.ajax({
      url : '../data/query_notification_appointment.php',
      type : 'POST',
      data : {qid : 2, name : name},
      success : function(data) {
        var json = JSON.parse(data);
        var cid = 'P-' + padZero(json.id, 5);
        var img = json.cust_image;
        var folder = '';
        if (img != '0') { folder = cid + '/'; }
        $('#cid').val(cid);
        $('#code').val(json.cust_code);
        $('#uploadedAvatar').attr('src','../images/profiles/' + folder + img + '.jpg');
      }
    });
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 4);
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
          alertText('Appointment has been saved to database!', 'primary');
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
    $('#name').next('.select2-container').hide();
    $('#name').prop('required', false);
    $('#showName').show();
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
        $('#showName').val(json.cust_fname);
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
          clearForm();
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
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Do you want to check in this patient to queue?")) {
      $.ajax({
        url: "../data/query_notification_appointment.php",
        data: { qid : 6, id: id, 
          pid : $('#pid').val()},
        type: 'POST',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Appointment has been added to queue!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });
 
  function clearForm() {
    $('#id').val(0);
    $('#cid').val('');
    $('#code').val('');
    $('#name').val(null).trigger('change');
    $('#showName').val('');
    $('#datetime').val(setNow());
    $('#duration').val(2);
    $('#sid').val(2);
    $('#note').val('');
    $('#uploadedAvatar').attr('src','../images/profiles/0.jpg');
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
            { extend: 'print', text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
            { extend: 'excelHtml5', text: '<i class="bx bx-file me-2"></i>Excel', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
            { extend: 'copyHtml5', text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
            { extend: 'colvis', text: '<i class="bx bx-grid me-2"></i>Column', className: "dropdown-item" },
        ]},{
            className: 'btn btn-primary ms-2',
            text: 'Calendar View',
            action: function (e, dt, node, config) { window.location.href="calendar.php" }
          },{
            className: 'btn btn-primary ms-2',
            text: '+ New Appointment',
            action: function (e, dt, node, config) {  
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('appointmentOffcanvas'));
    offcanvas.show(); }
          },

      ],
      pageLength: 25,
      serverSide: true,
      processing: true,
      searching : true,
      paging: true,
      bDestroy : true,
      order: [],
      ajax: { 
        url: '../data/query_notification_appointment.php',
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