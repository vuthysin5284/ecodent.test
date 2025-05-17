  let _table = null;
  $(document).ready(function() {
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    clearForm();
    $('.modal-dialog form').attr('id','addForm');
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var cid = $('#cid').val();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 3);
    formData.append('cid', cid);
    formData.append('uid', uid);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_patient_appointment.php",
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
    $.ajax({
      url : "../data/query_patient_appointment.php",
      data : {qid : 2, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        $('#datetime').val(json.appo_datetime);
        $('#duration').val(json.appo_duration);
        $('#note').val(json.appo_note);
        $('#sid').val(json.staff_id);
        $('#id').val(json.id);
      }
    });
  });

  $(document).on('submit', '#editForm', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('qid', 4);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_patient_appointment.php",
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
        url: "../data/query_patient_appointment.php",
        data: { qid : 0, id: id, 
          pid : $('#pid').val()},
        type: "post",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Appointment has been deleted from database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  function clearForm() {
    $('#id').val(0);
    $('#sid').val(2);
    $('#note').val('');
    $('#repeat').val(0);
    $('#datetime').val(setNow());
    $('#duration').val(2);
  }

  function getDataTable() {
    _table = $('#dataTable').DataTable({  
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
          className: 'btn btn-primary ms-2',
          text: 'All',
          action: function (e, dt, node, config) { _status = 'ALL'; _table.ajax.reload(); }
        },{
          className: 'btn btn-primary',
          text: 'Pending',
          action: function (e, dt, node, config) { _status = '1'; _table.ajax.reload(); }
        },{
          className: 'btn btn-primary',
          text: 'Completed',
          action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
        },{
          className: 'btn btn-primary',
          text: 'Cancelled',
          action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
        },
        {
          extend: 'collection',
          className: 'dropdown-toggle btn btn-label-secondary shadow-none',
          text: '<i class="bx me-2"></i> Type',
          buttons: [
            { text: 'ALL', action: function (e, dt, node, config) { _num_code = 'ALL'; _table.ajax.reload(); } },
            { text: 'Appointment', action: function (e, dt, node, config) { _num_code = 'Appointment'; _table.ajax.reload(); } },
            { text: 'Follow Up', action: function (e, dt, node, config) { _num_code = 'Follow Up'; _table.ajax.reload(); }} 
          ]
        },{
        extend: 'collection',
        className: 'dropdown-toggle btn btn-label-secondary shadow-none',
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
      searching: false,
      paging: true,
      info : true,
      order: [],
      ajax: {
        url: '../data/query_patient_appointment.php',
        type: 'POST',
        data: function(d){
          d.qid = 1;
          d.cid = $('#cid').val();
          d.uid = $('#navLog').val(); 
          d.pgid = $('#pgid').val();
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3,4,5],
        }]
    });
  }