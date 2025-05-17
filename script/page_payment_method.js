
  $(document).ready(function() {
    getDataTable();
  });

  $(document).on('click', '#create', function() {    
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('paymentmethodOffcanvas'));
    offcanvas.show();
    // clearForm();
    // $('.modal-dialog form').attr('id','addForm');
    // $('#code').val(randQr(8));
    // $.ajax({
    //   url : "../data/query_payment_method.php",
    //   data : {qid : 5},
    //   type : 'post',
    //   success : function(data) {
    //     var json = JSON.parse(data);
    //     var id = parseInt(json.id) + 1;
    //     $('#id').val(id);
    //   }
    // }); 
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var uid = '<?php echo $log; ?>';
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
      url : "../data/query_payment_method.php",
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
          alertText('Payment method has been saved to database!', 'primary');
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
      url : "../data/query_payment_method.php",
      data : {qid : 2, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        var cid = 'M-' + padZero(json.id,5);
        var img = json.method_image;
        var folder = '';
        if (img != '0') { folder = cid + '/'; }
        $('#id').val(json.id);
        $('#method').val(json.payment_method);
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
      url : "../data/query_payment_method.php",
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
          alertText('Payment method has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this method? ")) {
      $.ajax({
        url: "../data/query_payment_method.php",
        data: { qid : 0, id: id },
        type: "post",
        success: function(data) {
          var json = JSON.parse(data);
          status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Payment method has been deleted database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  function clearForm() {
    $('#id').val('');
    $('#image').val('');
    $('#method').val('');
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
        extend: 'collection',
        className: 'dropdown-toggle btn btn-label-secondary shadow-none ms-2',
        text: '<i class="bx bx-export me-2"></i>Export',
        buttons: [
          { extend: 'print', text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: {columns:[0,2]} },
          { extend: 'excelHtml5', text: '<i class="bx bx-file me-2"></i>Excel', className: "dropdown-item", exportOptions: {columns:[0,2]} },
          { extend: 'copyHtml5', text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: {columns:[0,2]} },
          { extend: 'colvis', text: '<i class="bx bx-grid me-2"></i>Column', className: "dropdown-item" },
        ]
      }],
      pageLength: 25,
      serverSide: true,
      processing: true,
      paging: true,
      order: [],
      ajax: {
        url: '../data/query_payment_method.php',
        type: 'POST',
        data : {qid : 1, 
          pid : $('#pid').val()}
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,3],
        }]
    });