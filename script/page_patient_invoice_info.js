
  $(document).ready(function() {
    $('#editIcon').attr("class", "bx bx-pencil");
    $('#title').attr("readonly", true);
    getData();
    getDataTable();
    getPaymentTable();
  });

  $(document).on('click', '#editInvoice', function() {
    if ($('#editInvoice').val() == 1) {
      $('#editIcon').attr("class", "bx bx-check");
      $('#title').attr("readonly", false);
      $('#title').focus();
      $('#editInvoice').val(0);
    } else {
      $('#editIcon').attr("class", "bx bx-pencil");
      $('#title').attr("readonly", true);
      $('#editInvoice').val(1);
    }
  });

  $(document).on('submit', '#addForm', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('qid', 4);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_patient_invoice_info.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        var cid = $('#cid').val();
        if (status == 'true') {
          window.location.href = "patient_invoice.php?pgid=" + $('#pid').val() + "&cid=" + cid;
        } else {
          alertText('Failed', 'Primary');
        }
      }
    });
  });

  function getData() {
    $.ajax({
      url : "../data/query_patient_invoice_info.php",
      data : {qid : 3, invid : $('#invid').val()},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        var category = json.inv_category;
        var title = json.inv_title;
        var now = new Date();
        var invoice_number = now.getFullYear() + '-' + padZero(json.id, 4);
        if (category == '0') { category = 5; }
        $('#id').val(json.id);
        $('#category').val(category);
        $('#title').val(json.inv_title);
        $('#status').val(json.inv_status);
        $('#dentist').val(json.user_id);
        $('#grandtotal').val(json.inv_grandtotal);
      }
    });
  }

  function getDataTable() {
    $('#dataTable').DataTable({  
      "fnCreatedRow": function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },  
      'pageLength': 25,
      'serverSide': true,
      'processing': true,
      'searching' : false,
      'paging': false,
      'info' : false,
      'order': [],
      'ajax': {
        'url': '../data/query_patient_invoice_info.php',
        'type': 'post',
        'data' : {
          "qid" : 1,
          "cid" : $('#cid').val(),
          "invid" : $('#invid').val(), 
          "pid" : $('#pid').val()
        }
      },
      "aoColumnDefs": [{
          "bSortable": false,
          "aTargets": [0,1,2,3,4,5,6],
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
      "fnCreatedRow": function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },  
      'pageLength': 25,
      'serverSide': true,
      'processing': true,
      'searching' : false,
      'paging': false,
      'info' : false,
      'order': [],
      'ajax': {
        'url': '../data/query_patient_invoice_info.php',
        'type': 'POST',
        'data' : {
          "qid" : 2,
          "cid" : $('#cid').val(),
          "invid" : $('#invid').val(),
          "uid" : $('#navLog').val(), 
          pid : $('#pid').val()
        }
      },
      "aoColumnDefs": [{
          "bSortable": false,
          "aTargets": [0,1,2,3,4,5],
        }],
        drawCallback : function(settings) {
        var total = settings.json.total;
        var remain = settings.json.remain;
        $('#totalpayment').html('<div class="d-flex justify-content-between p-0"><span>$</span><span>' + total + '</span></div>');
        $('#remainpayment').html('<div class="d-flex justify-content-between p-0"><span>$</span><span>- ' + remain + '</span></div>');
      }
    }); 
  }