
  var _rating = 0;
  $(document).ready(function() {
    $("#amount_en").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
    $("#amount_kh").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });

    _rating = Number($('#rating').val()); 

    getDataTable();
    getPaymentTable();
  });
  
  $(document).on('click', '#create', function() { 
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('invoicePaymentOffcanvas'));
    offcanvas.show();
    // $('.modal-payment').show();
    // $('.modal-dialog form').attr('id','addForm');
    // clearForm();
    // $.ajax({
    //   url : "../data/query_invoice_payment.php",
    //   data : {qid : 3, icode : $('#icode').val() },
    //   type : 'POST',
    //   success : function(data) {
    //     const nFormat = new Intl.NumberFormat();
    //     var json = JSON.parse(data);
    //     var remain = parseFloat(json.inv_remain);
    //     var remain_kh = remain * _rating;
    //     $('#remain').val(nFormat.format(remain));
    //     $('#remain_kh').val(nFormat.format(remain_kh));
    //   }
    // }); 
  });
  
  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var icode = $('#icode').val();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 4);
    formData.append('uid', uid );
    formData.append('icode', icode);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_invoice_payment.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        var pid = json.pid;
        if (status == 'true') {
          clearForm();
          $('#Modal').modal('hide');
          $('#paymentTable').DataTable().draw();
          alertText('Payment has been saved to database!', 'primary');
          telegramNotify(pid);
        } else {
          alertText('Failed', 'Primary');
        }
      }
    });
  });

  $('#paymentTable').on('click', '.editBtn', function() {   
    var table = $('#paymentTable').DataTable();
    var trid = $(this).closest('tr').attr('id');
    var id = $(this).data('id');
    clearForm();
    $('#inv_paym_id').val(id);
    $('.modal-payment').hide();
    $('#Modal').modal('show');
    $('.modal-dialog form').attr('id','editForm');
    $.ajax({
      url : "../data/query_invoice_payment.php",
      data : {qid : 6, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        $('#datetime').val(json.timestamp);
        $('#paym').val(json.paym_id);
        $('#note').val(json.payment_note);
      }
    });
  });

  $(document).on('submit', '#editForm', function (e) {
    e.preventDefault();
    var id = $('#inv_paym_id').val();
    var formData = new FormData(this);
    formData.append('qid', 7);
    formData.append('id', id);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_invoice_payment.php",
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
          $('#paymentTable').DataTable().draw();
          alertText('Payment has been updated to database!', 'primary');
        } else { alert('Failed'); }
        // alert(data);
      }
    });
  });
  
  $(document).on('click', '.deleteBtn', function() {
    var table = $('#paymentTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that want to delete this payment? ")) {
      $.ajax({
        url: "../data/query_invoice_payment.php",
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
  
  $('#amount_en').blur(function() {
    if ($('#amount_en').val() == '') {
      $('#amount_en').val(0);
    }
    const nFormat = new Intl.NumberFormat();
    var amount_en = parseFloat($('#amount_en').val());
    var amount_kh = parseFloat($('#amount_kh').val());
    var amount = amount_en + (amount_kh / _rating);
    var total = parseFloat($("#remain").val().replace(/,/g, ''));
    var change_en = amount - total;
    var change_kh = change_en * _rating;
    $('#change_en').val(nFormat.format(change_en));
    $('#change_kh').val(nFormat.format(parseInt(change_kh)));
  });

  $('#amount_kh').blur(function() {
    if ($('#amount_kh').val() == '') {
      $('#amount_kh').val(0);
    }
    const nFormat = new Intl.NumberFormat();
    var amount_en = parseFloat($('#amount_en').val());
    var amount_kh = parseFloat($('#amount_kh').val());
    var amount = amount_en + (amount_kh / _rating);
    var total = parseFloat($("#remain").val().replace(/,/g, ''));
    var change_en = amount - total;
    var change_kh = change_en * _rating;
    $('#change_en').val(nFormat.format(change_en));
    $('#change_kh').val(nFormat.format(parseInt(change_kh)));
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
        url: '../data/query_invoice_payment.php',
        type: 'POST',
        data : {
          qid : 1,
          icode : $('#icode').val(),
          uid : $('#navLog').val(), 
          pid : $('#pid').val()
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3,4,5,6],
        }],
      drawCallback : function(settings) {
        var grandtotal = settings.json.grandtotal;
        $('#subTotal').html(settings.json.subtotal);
        $('#totalDiscount').html(settings.json.totaldisc);
        $('#grandTotal').html('<div class="d-flex justify-content-between p-0"><span>$</span><span>' + grandtotal + '</span></div>');
        $('#grandtotal').val(grandtotal);
      }
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
        url: '../data/query_invoice_payment.php',
        type: 'POST',
        data : {
          qid : 2,
          icode : $('#icode').val(),
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
  
  function clearForm() {
    var now = new Date();
    var timezoneOffset = 7 * 60;
    var offsetDateTime = new Date(now.getTime() + timezoneOffset * 60000);
    var formattedDateTime = offsetDateTime.toISOString().slice(0,16).replace("T", " ");

    $('#amount_en').val(0);
    $('#amount_kh').val(0);
    $('#change_en').val(0);
    $('#change_kh').val(0);
    $('#paym').val('');
    $('#note').val('');
    $('#datetime').val(formattedDateTime);
    $('#toggleCurrency').text('$');
    $('#toggle').val(0);
  }

  function telegramNotify(id) {
    $.ajax({
      url : "../data/query_invoice_payment.php",
      data : {qid : 5, id : id },
      type : 'POST',
    }); 
  }