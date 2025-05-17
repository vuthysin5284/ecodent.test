
  $(document).ready(function() {
    var start = moment();
    var end = moment();
    cb(start, end);
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment()],
          'Tomorow': [moment().add(1, 'days'), moment().add(1, 'days')],
          'This Week': [moment().startOf('week'), moment().endOf('week')],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'This Year': [moment().startOf('year'), moment().endOf('year')],
        }
    }, cb);
    getDataTable();
  });

  $(document).on('click', '#btnFilter', function() {
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    clearForm();
    $('.modal-dialog form').attr('id','addForm');
    // $('#pcode').val(randQr(8));
    // $('#name').select2({
    //   ajax : {
    //     url : '../data/query_select_patient.php',
    //     dataType : 'json',
    //     data : function (params) {
    //       var query = {
    //         search : params.term,
    //         type : 'cust_search',
    //       };
    //       return query;
    //     },
    //     processResults : function (data) {
    //       return {
    //         results : data
    //       }
    //     }
    //   },
    //   cache : true,
    //   disabled: '',
    //   theme: "bootstrap-5",
    //   dropdownParent: "#Modal",
    //   closeOnSelect: true,
    //   placeholder: '--- select patient ---',
    // });
  });

  $(document).on('change', '#name', function(){
    var name = $('#name').val();
    $.ajax({
      url : '../data/query_notification_diagnosis.php',
      type : 'POST',
      data : {qid : 2, name : name},
      success : function(data) {
        var json = JSON.parse(data);
        var cid = 'P-' + padZero(json.id, 5);
        var img = json.cust_image;
        var folder = '';
        if (img != '0') { folder = cid + '/'; }
        $('#cid').val(cid);
        $('#code').val(json.cust_code);
        $('#uploadedAvatar').attr('src','../images/profiles/' + folder + img + '.jpg');
      }
    });
  });
  
  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 4);
    formData.append('uid', uid);

    
  //   $('table#order_items-tbl tbody tr').each(function() {
  //     var id =$(this).attr('id');
  //     console.log(id);
  //     var qty =  $('#qty'+id).val();
  //     var unit_price =  $('#unit_price'+id).val();
  //     var menu_id =  $('#itemid'+id).val();
  //     var menu_name =  $('#itemname'+id).val();
  //     var menu_name_en =  $('#itemname_en'+id).val();
  //     var uom_name =  $('#spUOM'+id).text();
  //     var prod_code =  $('#prod_code'+id).val();

  //     _arr.push({name: 'qty[]', value: qty});
  //     _arr.push({name: 'unit_price[]', value: unit_price});
  //     _arr.push({name: 'product_id[]', value: menu_id});
  //     _arr.push({name: 'prod_code[]', value: prod_code});
  //     _arr.push({name: 'product_khmer[]', value: menu_name});
  //     _arr.push({name: 'product_name[]', value: menu_name_en});
  //     _arr.push({name: 'uom_name[]', value: uom_name});
  // });  

    $.ajax({
      type        : "POST",
      enctype     : "multipart/form-data",
      url         : "../data/query_notification_diagnosis.php",
      data        : formData,
      cache       : false,
      contentType : false,
      processData : false,
      success     : function(data) {
        var json    = JSON.parse(data);
        var status  = json.status;
        if(status == 'true') {              
          $('#Modal').modal('hide');
          $('#dataTable').DataTable().draw();
          alertText('Diagnosis has been saved to database!', 'primary');
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
    $("#name").select2({ theme: "bootstrap-5", disabled:'readonly' });
    $.ajax({
      url : "../data/query_notification_diagnosis.php",
      data : {qid : 3, id : id, 
        pid : $('#pid').val()},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        var cid = 'P-' + padZero(json.cust_id, 5);
        var img = json.cust_image;
        var folder = '';
        if (img != '0') { folder = cid + '/'; }
        $('#id').val(id);
        $('#cid').val(cid);
        $('#code').val(json.cust_code);
        $('#pcode').val(json.pres_code);
        $('#name').val(json.cust_id).trigger('change');
        $('#diagnosis').val(json.pres_diagnosis);
        $('#uploadedAvatar').attr('src','../images/profiles/' + folder + img + '.jpg');
      }
    });
  });

  $(document).on('submit', '#editForm', function (e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 5);
    formData.append('uid', uid);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_notification_diagnosis.php",
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
          alertText('Diagnosis has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this appointment? ")) {
      $.ajax({
        url: '../data/query_notification_diagnosis.php',
        data: { qid : 0, id: id },
        type: 'POST',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Appointment has been deleted from database!', 'primary');
          } else {
            alert(data);
            return;
          }
        }
      });
    } else { return null; }
  });

  function clearForm() {
    $('#id').val('');
    $('#cid').val('');
    $('#code').val('');
    $('#pcode').val('');
    $('#name').val(null).trigger('change');
    $('#diagnosis').val('');
    $('#uploadedAvatar').attr('src','../images/profiles/0.jpg');
  }

  function cb(start, end) {
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
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
      pageLength: 25,
      serverSide: true,
      processing: true,
      searching : false,
      paging: false,
      info : true,
      order: [],
      ajax: { 
        url: '../data/query_notification_diagnosis.php',
        type: 'POST',
        data : {
          qid : 1,
          // date : $('#date').val(),
          tmpid : $('#tmpid').val(), 
          cid : $('#cid').val(), 
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