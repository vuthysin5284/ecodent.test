
  $(document).ready(function() {
    $("#salary").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
    $("#commission").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
    $("#contact").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9]/g, '')); });
  });

  $(document).on('click', '#create', function() {
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('employeeOffcanvas'));
    offcanvas.show();
    // clearForm();
    // $('.modal-dialog form').attr('id','addForm');
    // $('#code').val(randQr(8));
    // $.ajax({
    //   url : "../data/query_staff_list.php",
    //   data : {qid : 5},
    //   type : 'POST',
    //   success : function(data) {
    //     var json = JSON.parse(data);
    //     var id = json.id;
    //     if (id == null) { id = 1 } else {id = parseInt(id) + 1; }
    //     var sid = 'S-' + padZero(id,5);
    //     $('#id').val(sid);
    //   }
    // }); 
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append("qid", 3);
    formData.append("uid", uid);
    $.ajax({
      xhr : function() {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function (evt) {
          if(evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);
            $('#progressbar').html(percentComplete + '%');
            $('#progressbar').width(percentComplete + '%');
          }
        }, false);
        return xhr;
      },
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_staff_list.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'true') {
          $('#progressbar').html(0 + '%');
          $('#progressbar').width(0 + '%');
          $('#Modal').modal('hide');
          $('#dataTable').DataTable().draw();
          alertText('Staff has been saved to database!', 'primary');
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
      url : "../data/query_staff_list.php",
      data : {qid : 2, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        var cid = 'S-' + padZero(json.id,5);
        var gender = json.staff_gender;
        var img = json.staff_image;
        var folder = '';
        if (img != '0') { folder = cid + '/'; }
        if(gender == 1) { $('#gender').val('1').change(); }
        else { $('#gender').val('0').change(); }
        $('#id').val(cid);
        $('#code').val(json.staff_code);
        $('#name').val(json.staff_fname);
        $('#dob').val(json.staff_dob);
        $('#contact').val(json.staff_contact);
        $('#position').val(json.staff_position_id);
        $('#address').val(json.staff_address);
        $('#salary').val(json.staff_salary);
        $('#commission').val(json.staff_commission);
        $('#uploadedAvatar').attr('src','../images/profiles/' + folder + img + '.jpg');
      }
    });
  });

  $(document).on('submit', '#editForm', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append("qid", 4);
    $.ajax({
      xhr : function() {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function (evt) {
          if(evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);
            $('#progressbar').html(percentComplete + '%');
            $('#progressbar').width(percentComplete + '%');
          }
        }, false);
        return xhr;
      },
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_staff_list.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'true') {
          clearForm();
          $('#progressbar').html(0 + '%');
          $('#progressbar').width(0 + '%');
          $('#Modal').modal('hide');
          $('#dataTable').DataTable().draw();
          alertText('Staff has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });
  /* Click Delete Button */
  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this staff? ")) {
      $.ajax({
        url: '../data/query_staff_list.php',
        data: { qid : 0, id: id },
        type: 'POST',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Staff has been deleted database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  function clearForm() {
    $('#upload').val('');
    $('#name').val('');
    $('#position').val(1);
    $('#gender').val(1);
    $('#dob').val('1998-01-01');
    $('#contact').val('');
    $('#address').val('');
    $('#salary').val(0);
    $('#commission').val(0);
    $('#uploadedAvatar').attr('src','../images/profiles/0.jpg');
  }

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
          text: 'Active',
          action: function (e, dt, node, config) { _status = '1'; _table.ajax.reload(); }
        },{
          className: 'btn btn-primary',
          text: 'Delete',
          action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
        },
        {
          extend: 'collection',
          className: 'dropdown-toggle btn btn-label-secondary shadow-none',
          text: '<i class="bx me-2"></i> Type',
          buttons: [
            { text: 'ALL', action: function (e, dt, node, config) { _num_code = 'ALL'; _table.ajax.reload(); } },
            { text: 'Staff', action: function (e, dt, node, config) { _num_code = 'Staff'; _table.ajax.reload(); } },
            { text: 'Doctor', action: function (e, dt, node, config) { _num_code = 'Doctor'; _table.ajax.reload(); }},
            { text: 'Assistant', action: function (e, dt, node, config) { _num_code = 'Assistant'; _table.ajax.reload(); } }, 
          ]
        },{
          extend: 'collection',
          className: 'dropdown-toggle btn btn-label-secondary shadow-none',
          text: '<i class="bx bx-export me-2"></i>Export',
          buttons: [
            { extend: 'print', text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
            { extend: 'excelHtml5', text: '<i class="bx bx-file me-2"></i>Excel', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
            { extend: 'copyHtml5', text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
            { extend: 'colvis', text: '<i class="bx bx-grid me-2"></i>Column', className: "dropdown-item" },
          ]
        },{
          className: 'btn btn-primary ms-2',
          text: '+ New Employee',
          action: function (e, dt, node, config) { $('#create').trigger('click'); }
        }
      ],  
      pageLength: 25,
      serverSide: true,
      processing: true, 
      searching: true,
      paging: true,
      order: [],
      ajax: {
        url: '../data/query_staff_list.php',
        type: 'POST',
        data : {qid : 1, 
          pid : $('#pid').val()}
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,9],
        }]
  });