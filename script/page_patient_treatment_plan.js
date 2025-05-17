
  $(document).ready(function() {
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    var cid = $('#cid').val();
    var uid = $('#navLog').val();
    if(confirm("Are you sure you want to starting serving patient?")){ 
      $.ajax({
        url : "../data/query_patient_treatment_plan.php",
        data : {
          qid : 3,
          cid : cid,
          uid : uid,
          pid : $('#pid').val(),
          apid : $('#apid').val()
        },
        type : 'POST',
        success : function(data) {
          var json = JSON.parse(data);
          var ccode = $('#cid').val();
          window.location.href = 'patient_treatment.php?pgid=' +$('#pid').val()+'&cid=' + ccode + '&tmpid=' + json.tmpid+ '&apid=' + $('#apid').val();
        }
      });
    }
    // else{
    //   alert("You are cancelling starting serving patient.");
    // }

  });

  $('#dataTable').on('click', '.editBtn', function() {   
    var table = $('#dataTable').DataTable();
    var trid = $(this).closest('tr').attr('id');
    var id = $(this).data('id');
    clearForm();
    $('#Modal').modal('show');
    $('.modal-dialog form').attr('id','editForm');
    $.ajax({
      url : "../data/query_patient_treatment_plan.php",
      data : {qid : 2, id : id, 
        pid : $('#pid').val()},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        $('#title').val(json.plan_title);
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
      url : "../data/query_patient_treatment_plan.php",
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
          alertText('Treatment plan has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });
  
  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this treatment plan? ")) {
      $.ajax({
        url: '../data/query_patient_treatment_plan.php',
        data: { qid : 0, id: id , 
          pid : $('#pid').val()},
        type: 'POST',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Treatment plan has been deleted from database!', 'primary');
          } else {
            alert(data);
            return;
          }
        }
      });
    } else { return null; }
  });

  function clearForm() {
    $('#id').val(0);
    $('#title').val('');
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
        url: '../data/query_patient_treatment_plan.php',
        type: 'post',
        data : {
          qid : 1,
          cid : $('#cid').val(),
          uid : $('#navLog').val(), 
          pid : $('#pid').val(),
          apid : $('#apid').val()
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3,4],
        }]
    }); 
  }