
let _table = null;
let _status = "ALL";
let _num_code = "ALL";
$(document).ready(function() {
  $("#qty").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
  $("#cost").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
  $("#min").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
  getDataTable();
});

$(document).on('click', '#create', function() {
  clearForm();
  $('.modal-dialog form').attr('id','addForm');
  $('#code').val(randQr(8));
  $.ajax({
    url : "../data/query_product_list.php",
    data : {qid : 5},
    type : 'POST',
    success : function(data) {
      var json = JSON.parse(data);
      var id = json.id;
      if (id == null) { id = 1 } else {id = parseInt(id) + 1; }
      var sid = 'PRO-' + padZero(id,5);
      $('#id').val(sid);
    }
  }); 
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
    url : "../data/query_product_list.php",
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
        alertText('Product has been saved to database!', 'primary');
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
    url : "../data/query_product_list.php",
    data : {qid : 2, id : id},
    type : 'POST',
    success : function(data) {
      var json = JSON.parse(data);
      var pid = 'PRO-' + padZero(json.id,5);
      var img = json.prod_image;
      var folder = '';
      if (img != '0') { folder = pid + '/'; }
      $('#id').val(pid);
      $('#code').val(json.prod_code);
      $('#description').val(json.prod_description);
      $('#categ').val(json.prod_cate_id);
      $('#suppid').val(json.supp_id);
      $('#qty').val(json.prod_qty);
      $('#cost').val(json.prod_unit_cost);
      $('#min').val(json.prod_min_qty);
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
    url : "../data/query_product_list.php",
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
        alertText('Product has been updated to database!', 'primary');
      } else { alert('Failed'); }
    }
  });
});

$(document).on('click', '.deleteBtn', function() {
  var table = $('#dataTable').DataTable();
  var id = $(this).data('id');
  if (confirm("Are you sure that you want to delete this product? ")) {
    $.ajax({
      url: "../data/query_product_list.php",
      data: { qid : 0, id: id },
      type: "POST",
      success: function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if (status == 'success') {
          _table.ajax.reload();
          alertText('Product has been deleted database!', 'primary');
        } else { alert(data); return; }
      }
    });
  } else { return null; }
});

function clearForm() {
  $('#upload').val('');
  $('#id').val('');
  $('#code').val('');
  $('#description').val('');
  $('#categ').val('');
  $('#suppid').val('');
  $('#qty').val('');
  $('#cost').val('');
  $('#min').val('');
  $('#uploadedAvatar').attr('src','../images/profiles/0.jpg');
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
        text: 'View',
        action: function (e, dt, node, config) { _status = '1'; _table.ajax.reload(); }
      },{
        className: 'btn btn-primary',
        text: 'Approval',
        action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
      },{
        className: 'btn btn-primary',
        text: 'Inventory',
        action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
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
      },
    ],  
    pageLength: 25,
    serverSide: true,
    processing: true, 
    searching: true,
    paging: true,
    order: [],
    ajax: {
      url: '../data/query_product_list.php',
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
        aTargets: [0,8],
      }]
  });
}