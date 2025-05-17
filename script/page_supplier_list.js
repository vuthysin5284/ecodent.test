
  $(document).ready(function() {
    $("#contact").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9]/g, '')); });
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('supplierOffcanvas'));
    offcanvas.show();
    // clearForm();
    // $('.modal-dialog form').attr('id','addForm');
    // $('#code').val(randQr(8));
    // $.ajax({
    //   url : "../data/query_stock_supplier.php",
    //   data : {qid : 5},
    //   type : 'POST',
    //   success : function(data) {
    //     var json = JSON.parse(data);
    //     var id = json.id;
    //     if (id == null) { id = 1 } else {id = parseInt(id) + 1; }
    //     var sid = 'SUP-' + padZero(id,5);
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
      url : "../data/query_stock_supplier.php",
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
          alertText('Supplier has been saved to database!', 'primary');
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
      url : "../data/query_stock_supplier.php",
      data : {qid : 2, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        var cid = 'SUP-' + padZero(json.id,5);
        var folder = '';
        var img = json.supp_image;
        if (img != '0') { folder = cid + '/'; }
        $('#id').val(cid);
        $('#code').val(json.supp_code);
        $('#name').val(json.supp_fname);
        $('#category').val(json.exp_cate_id);
        $('#contact').val(json.supp_contact);
        $('#address').val(json.supp_address);
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
      url : "../data/query_stock_supplier.php",
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
          alertText('Supplier has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

   $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this supplier? ")) {
      $.ajax({
        url: "../data/query_stock_supplier.php",
        data: { qid : 0, id: id },
        type: "post",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Supplier has been deleted database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  function clearForm() {
    $('#upload').val('');
    $('#name').val('');
    $('#category').val('');
    $('#contact').val('');
    $('#address').val('');
    $('#uploadedAvatar').attr('src','../images/profiles/0.jpg');
  }

  function getDataTable() {
    $('#dataTable').DataTable({  
      fnCreatedRow: function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },
      drawCallback: function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
      },
      dom: '<"d-flex justify-content-between"<""l><""f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      language : {
        sLengthMenu : '_MENU_',
        search : '',
        searchPlaceholder : 'Search',
      },
      pageLength: 25,
      serverSide: true,
      processing: true,
      paging: true,
      order: [],
      ajax: {
        url: '../data/query_stock_supplier.php',
        type: 'POST',
        data : {
          "qid" : 1,
          "uid" : $('#navLog').val(), 
          pid : $('#pid').val()
        }
      },
      "aoColumnDefs": [{
          "bSortable": false,
          "aTargets": [0,5],
        }]
    });
  }