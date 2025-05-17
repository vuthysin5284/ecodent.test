function getRows() {
    var cid = $('#cid').val();
    var tmpid = $('#tmpid').val();
    $.ajax({
      url : '../data/query_patient_treatment.php',
      type : 'POST',
      data : {qid : 1, cid : cid, tmpid : tmpid,pid : $('#pid').val()},
      success : function (data) {
        $('#mytable').html(data);
      }
    });
  }
  var _is_invoice = 0;
  $(document).ready(function() {
    _is_invoice = $("#is_invoice").val(); 
    //disable button generate invoice 
    if(_is_invoice==1){
      $('#btnGenerateInvoice').prop("disabled", true);
    }
    //
    $('#service').select2({ theme: "bootstrap-5", dropdownParent: "#Modal", placeholder: $(this).data( 'placeholder'), });
    $('#tooth').select2({ theme: "bootstrap-5", dropdownParent: "#Modal", placeholder: $(this).data( 'placeholder'), });
    $('#price').on('input', function(e) {
      $(this).val($(this).val().replace(/[^0-9.]/g, ''));
    });
    $("#discount").on('input', function(e) {
      $(this).val($(this).val().replace(/[^0-9.]/g, ''));
    });
    $("#totalDiscount").on('input', function(e) {
      $(this).val($(this).val().replace(/[^0-9.]/g, ''));
    });
    $('#typeDisc').val('0');
    getDataTable();
  });

  $(document).on('change', '#service', function() {
    var svid = $('#service').val();
    $.ajax({
      url : '../data/query_patient_treatment.php',
      type : 'POST',
      data : {qid : 3, svid : svid,pid : $('#pid').val()},
      success : function(data) {
        var json = JSON.parse(data);
        var price = parseFloat(json.service_price);
        var count = $('#tooth').val().length;
        var disc = $('#discount').val();
        var total = (count * price) - ((count * price * disc) / 100 );
        $('#price').val(price.toFixed(2));
        $('#total').val(total.toFixed(2));
      }
    });
  });

  $(document).on('click', '#checkAll', function () {
    if (this.checked) {
      $(".checkitem").prop("checked", true);
    } else {
      $(".checkitem").prop("checked", false);
    }
  });

  $(document).on('change', '#tooth', function() {
    var count = $('#tooth').val().length;
    var price = $('#price').val();
    var disc = $('#discount').val();
    var total = (count * price) - ((count * price * disc) / 100 );
    $('#qty').val(count);
    $('#total').val(total.toFixed(2));
  });

  $('#discount').blur(function() {
    var disc = $(this).val();
    if (disc == '') { $('#discount').val(0) }
    else if (disc > 100) { $('#discount').val(100) }
    else {
      var count = $('#tooth').val().length;
      var price = $('#price').val();
      var total = (count * price) - ((count * price * disc) / 100 );
      $('#qty').val(count);
      $('#total').val(total.toFixed(2));
    }
  });

  $('#price').blur(function() {
    var disc = $('#discount').val();
    if (disc == '') { $('#discount').val(0); }
    else if (disc > 100) { $('#discount').val(100); }
    else {
      var count = $('#tooth').val().length;
      var price = $('#price').val();
      var total = (count * price) - ((count * price * disc) / 100 );
      $('#qty').val(count);
      $('#total').val(total.toFixed(2));
    }
  });

  $(document).on('click', '#create', function() {
    $('.modal-dialog form').attr('id','addForm');
    clearForm();
  });
  $(document).on('click', '#createProduct', function() {
    $('.modal-dialog form').attr('id','addForm');
    clearForm();
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var tmpid = $('#tmpid').val();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 2);
    formData.append('tmpid', tmpid);
    formData.append("uid", uid);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_patient_treatment.php",
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
          alertText('Service has been saved to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this item? ")) {
      $.ajax({
        url: "../data/query_patient_treatment.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Service item has been deleted from database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  $(document).on('submit', '#check', function(e) {
    e.preventDefault();
    var ccode = $('#cid').val();
    var grandtotal = $('#grandTotal').val();
    var change_kh = $('#grandTotal').val()*Number($('#rating').val());
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    var icode = randQr(8);
    formData.append("qid", 4);
    formData.append("ccode", ccode);
    formData.append("tmpid", $('#tmpid').val());
    formData.append("icode", icode);
    formData.append("uid", uid);
    formData.append("grandtotal", grandtotal);
    formData.append("change_kh", change_kh);
    // 
    if (confirm("Are you sure that you want to proceed invoice? ")) {
      $.ajax({
        type : "POST",
        enctype : "multipart/form-data",
        url : "../data/query_patient_treatment.php",
        data : formData,
        cache : false,
        contentType : false,
        processData : false,
        success : function(data) {
          var json = JSON.parse(data); 
          if (json.status == 'true') {
            // window.location.href = 'patient_invoice_info.php?pgid=' + $('#pid').val() + '&cid=' + json.ccode + '&invid=' + json.invid; 
            // window.location.href = 'patient_invoice_info.php?pgid=' + $('#pid').val() + '&cid=' + json.ccode + '&invid=' + json.invid; 
            alertText('The treatmentservices item been sending invoice!', 'primary');
            window.location.href = 'patient_treatment.php?pgid=' + $('#pid').val() + '&cid=' + json.ccode + '&tmpid=' + json.invid+'&is_invoice=1&apid='+$('#apid').val(); 
            $('#btnGenerateInvoice').prop("disabled", true);
            
          } else {
            alert('Please select atleast one service to create invoice !');
          }
        }
      });
    }
    else{return null;}
  });

  $(document).on('click', '#editPrice', function() {
    if ($('#editPrice').val() == 1) {
      $('#editIcon').attr("class", "bx bx-check");
      $('#price').attr("readonly", false);
      $('#price').focus();
      $('#editPrice').val(0);
    } else {
      $('#editIcon').attr("class", "bx bx-pencil");
      $('#price').attr("readonly", true);
      $('#editPrice').val(1);
    }
  });

  $(document).on('click', '#toggleDisc', function() {
    var grandTotal;
    var toggle = $('#toggleDisc').val();
    var subTotal = $('#subTotal').val();
    var totalDiscount = $('#totalDiscount').val();
    if (toggle == '%') {
      $('#toggleDisc').val('$');
      $('#typeDisc').val('1');
      grandTotal = subTotal - totalDiscount;
    } else {
      $('#toggleDisc').val('%');
      $('#typeDisc').val('0');
      grandTotal = subTotal - (subTotal * totalDiscount / 100);
    }
    $('#grandTotal').val(grandTotal.toFixed(2));
  });

  $('#totalDiscount').blur(function() {
    var value = $(this).val();
    var toggle = $('#toggleDisc').val();
    var subTotal = parseFloat($('#subTotal').val());
    if (toggle == '%') {
      if (value == '') { value = 0; } else if (value > 100) { value = 100; } else { value = value; }
    } else {
      if (value == '') { value = 0; } else if (value > subTotal) { value = subTotal; } else { value = value; }
    }
    $('#totalDiscount').val(value);
    $('#grandTotal').val(calGrandTotal(subTotal, value, toggle));
  });

  function calGrandTotal(total, discount, toggle) {
    var grandtotal;
    if (toggle == '%') {
      grandtotal = total - (total * discount / 100);
    } else {
      grandtotal = total - discount;
    }
    return grandtotal.toFixed(2);
  }

  function clearForm() {
    $('#price').attr("readonly", true);
    $('#editIcon').attr("class", "bx bx-pencil");
    $('#tooth').val(null).trigger('change');
    $('#service').val(null).trigger('change');
    $('#price').val(0);
    $('#qty').val(0);
    $('#discount').val(0);
    $('#total').val(0);
  }

  function getDataTable() {
    $('#dataTable').DataTable({  
      fnCreatedRow: function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },
      drawCallback: function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
      },
      pageLength: 25,
      serverSide: true,
      processing: false,
      searching : false,
      paging: false,
      info : false,
      order: [],
      ajax: {
        url: '../data/query_patient_treatment.php',
        type: 'POST',
        data : {
          qid : 1,
          cid : $('#cid').val(),
          tmpid : $('#tmpid').val(), 
          pid : $('#pid').val(),
          is_invoice : $('#is_invoice').val()
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3,4,5,6,7],
        }],
      drawCallback : function(settings) {
        var subtotal = settings.json.subtotal;
        var totaldisc = settings.json.memb_discount;
        var grandtotal = subtotal - (subtotal * totaldisc / 100);
        $('#subTotal').val(subtotal.toFixed(2));
        $('#totalDiscount').val(totaldisc);
        $('#grandTotal').val(grandtotal.toFixed(2));
      }
    }); 
  }
