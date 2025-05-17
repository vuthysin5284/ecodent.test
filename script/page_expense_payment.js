  
  $(document).ready(function() {
    $("#amount").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
    $('#categ').change(function () {
      var str = $(this).val();
      $.ajax({
        url : '../data/query_expense_list.php',
        data : { qid : 6, str : str },
        type : 'POST',
        success: function(data) {
          $('#supplier').html(data);
        }
      });
    });
    getDataTable();
    getImage();
    getPaymentTable();
  });

  $(document).on('click', '#create', function() {    
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('expensePaymentOffcanvas'));
    offcanvas.show();
    // $('.modal-dialog form').attr('id','addForm');
    // clearForm();
    // $.ajax({
    //   url : "../data/query_expense_payment.php",
    //   data : {qid : 3, excode : $('#excode').val() },
    //   type : 'POST',
    //   success : function(data) {
    //     var json = JSON.parse(data);
    //     var remain = parseFloat(json.exp_remain);
    //     $('#remain').val(remain.toFixed(2));
    //   }
    // }); 
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#paymentTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that want to delete this payment? ")) {
      $.ajax({
        url: "../data/query_expense_payment.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#paymentTable').DataTable().ajax.reload();
            alertText('Payment has been deleted from database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  $(document).on('submit', '#imgForm', function(e) {
    e.preventDefault();
    var excode = $('#excode').val();
    var formData = new FormData(this);
    formData.append('qid', 8);
    formData.append('excode', excode);
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
      url : "../data/query_expense_payment.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'true') {              
        getImage();
        $('#progressbar').html(0 + '%');
        $('#progressbar').width(0 + '%');
        $('#files').val('');
        }
      }
    });
  });
  
  $(document).on('click', '.deleteImage', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure want to delete this Image? ")) {
      $.ajax({
        url: "../data/query_expense_payment.php",
        data: { qid : 9, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            getImage();
            alertText('Image has been deleted from database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });
  
  $(document).on('submit', '#addForm', function (e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var excode = $('#excode').val();
    var formData = new FormData(this);
    formData.append('qid', 4);
    formData.append('uid', uid );
    formData.append('excode', excode);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_expense_payment.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if (status == 'true') {
          clearForm();
          $('#Modal').modal('hide');
          $('#paymentTable').DataTable().draw();
          alertText('Payment has been saved to database!', 'primary');
        } else {
          alertText('Failed', 'Primary');
        }
      }
    });
  });

  function getDataTable() {
    $('#dataTable').DataTable({  
      fnCreatedRow: function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },
      pageLength: 25,
      serverSide: true,
      processing: true,
      searching : false,
      paging: false,
      info : false,
      order: [],
      ajax: {
        url: '../data/query_expense_payment.php',
        type: 'POST',
        data : {
          qid : 1, 
          excode : $('#excode').val(), 
          pid : $('#pid').val()
        }
      },
      aoColumnDefs: [{
        bSortable: false,
        aTargets: [0,1,2,3,4],
      }]
    });
  }

  function getPaymentTable() {
    $('#paymentTable').DataTable({  
      fnCreatedRow: function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },  
      pageLength: 25,
      serverSide: true,
      processing: true,
      searching : false,
      paging: false,
      info : false,
      order: [],
      ajax: {
        url: '../data/query_expense_payment.php',
        type: 'POST',
        data : {
          qid : 2,
          excode : $('#excode').val(),
          uid : $('#navLog').val(), 
          pid : $('#pid').val()
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3,4,5],
        }],
      drawCallback : function(settings) {
        $('[data-bs-toggle="tooltip"]').tooltip();
        var total = settings.json.total;
        var remain = settings.json.remain;
        $('#totalpayment').html('<div class="d-flex justify-content-between p-0"><span>$</span><span>' + total + '</span></div>');
        $('#remainpayment').html('<div class="d-flex justify-content-between p-0"><span>$</span><span>- ' + remain + '</span></div>');
      }
    }); 
  }

  function getImage() {
    $.ajax({
      url : '../data/query_expense_payment.php',
      type : 'POST',
      data : {
        qid : 7,
        excode : $('#excode').val(),
      },
      success : function(data) {
        $('#imageData').html(data);
      }
    });
  }

  function clearForm() {
    $('#amount').val('');
    $('#paym').val('');
    $('#note').val('');
  }