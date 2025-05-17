
  let _tableservice = null;
  let _status= "ALL";
  let _num_code= "ALL";
  $(document).ready(function() {
    $("#price").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
    $("#cost").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('serviceOffcanvas'));
    offcanvas.show();
    // clearForm();
    // $('.modal-dialog form').attr('id','addForm');
    // $.ajax({
    //   url : "../data/query_treatment_service.php",
    //   data : { qid : 5 },
    //   type : 'POST',
    //   success : function(data) {
    //     var json = JSON.parse(data);
    //     var id = parseInt(json.id) + 1;
    //     $('#id').val(id);
    //   }
    // }); 
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('qid', 3);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_treatment_service.php",
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
          alertText('Service has been saved to database!', 'primary');
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
      url : "../data/query_treatment_service.php",
      data : {qid : 2, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        $('#id').val(json.id);
        $('#service').val(json.service_description);
        $('#category').val(json.service_cate_id);
        $('#price').val(json.service_price);
        $('#cost').val(json.service_cost);
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
      url : "../data/query_treatment_service.php",
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
          alertText('Service has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure want to delete this menu? ")) {
      $.ajax({
        url: "../data/query_treatment_service.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'True') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Service has been deleted from database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  function clearForm() {
    $('#id').val('');
    $('#service').val('');
    $('#price').val('');
    $('#cost').val('');
  }

  function getDataTable() {
    _tableservice = $('#dataTable').DataTable({  
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
          action: function (e, dt, node, config) { _status = 'ALL'; _tableservice.ajax.reload(); }
        },{
          className: 'btn btn-primary',
          text: 'Active',
          action: function (e, dt, node, config) { _status = '1'; _tableservice.ajax.reload(); }
        },{
          className: 'btn btn-primary',
          text: 'Inactive',
          action: function (e, dt, node, config) { _status = '0'; _tableservice.ajax.reload(); }
        },
        {
          extend: 'collection',
          className: 'dropdown-toggle btn btn-label-secondary shadow-none',
          text: '<i class="bx me-2"></i> Type',
          buttons: [
            { text: 'ALL', action: function (e, dt, node, config) { _num_code = 'ALL'; _tableservice.ajax.reload(); } },
            { text: 'Orthodontics', action: function (e, dt, node, config) { _num_code = 'Orthodontics'; _tableservice.ajax.reload(); } },
            { text: 'Dental Implant', action: function (e, dt, node, config) { _num_code = 'Dental Implant'; _tableservice.ajax.reload(); }},
            { text: 'Restoration', action: function (e, dt, node, config) { _num_code = 'Restoration'; _tableservice.ajax.reload(); } },
            { text: 'Minor Surgery', action: function (e, dt, node, config) { _num_code = 'Minor Surgery'; _tableservice.ajax.reload(); }},
            { text: 'General', action: function (e, dt, node, config) { _num_code = 'General'; _tableservice.ajax.reload(); }},
            { text: 'Dental Lab', action: function (e, dt, node, config) { _status = 'Dental Lab'; _tableservice.ajax.reload(); }},
          ]
        }
        ,{
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
      pageLength: 100,
      serverSide: true,
      processing: true,
      paging: true,
      searching : true,
      order: [],
      ajax: {
        url: '../data/query_treatment_service.php',
        type: 'POST',
        data : function(d){
          d.qid = 1;
          d.status = _status;
          d.num_code = _num_code;
          d.pgid = $('#pgid').val();
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,4],
        }]
    });
  }