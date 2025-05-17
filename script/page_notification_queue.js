
  $(document).ready(function() {
    getDataTable();
  });
  
  $('#ccode').keyup(function() {
    var str = $('#ccode').val();
    if(str == ''){
      $('#searchCust').hide();
    } else {
      searchData(str);
      $('#searchCust').show();
    }
  });
  
  $(document).on('click', '.btnCust', function() {
    var id = $(this).data('id');
    $('#ccode').val(id);
    $('#searchCust').hide();
  });
  
  $('#dataTable').on('click', '.editBtn', function() {   
    var table = $('#dataTable').DataTable();
    var trid = $(this).closest('tr').attr('id');
    var id = $(this).data('id');
    clearForm();
    $('#Modal').modal('show');
    $('.modal-dialog form').attr('id','editForm');
    $.ajax({
      url : '../data/query_notification_queue.php',
      data : {qid : 2, id : id},
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
        $('#name').val(json.cust_fname);
        $('#duration').val(json.queue_duration);
        $('#note').val(json.queue_note);
        $('#staff').val(json.staff_id);
        $('#uploadedAvatar').attr('src','../images/profiles/' + folder + img + '.jpg');
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
      url : "../data/query_notification_queue.php",
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
          alertText('Queue has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });
  /* Click Delete Button */
  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this patient from queue? ")) {
      $.ajax({
        url: "../data/query_notification_queue.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Patient has been deleted from queue!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });
  
  $(document).on('click', '.showBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Do you want to check in this patient to serving?")) {
      $.ajax({
        url: "../data/query_notification_queue.php",
        data: { qid : 6, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Patient has been added to serving!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });
  
  $(document).on('submit', '#checkin', function (e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append("qid", 7);
    formData.append("uid", uid);
    $.ajax({
      url: "../data/query_notification_queue.php",
      data: formData,
      type: "POST",
      cache : false,
      contentType : false,
      processData : false,
      success: function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if (status == 'inQueue') {
          $('#ccode').val('');
          $('#searchCust').hide();
          alertText('Patient is already in queue!', 'primary');
        }
        else if (status == 'success') {
          $('#ccode').val('');
          $('#searchCust').hide();
          $('#dataTable').DataTable().ajax.reload();
          alertText('Patient has been added to queue!', 'primary');
        } else { alert(data); return; }
      }
    });
  });
  
  function searchData(str) {
    $.ajax({
      url : '../data/query_notification_queue.php',
      type : 'POST',
      data : {qid : 8, str : str},
      success : function(data) {
        $('#searchCust').html(data);
      }
    });
  }
  function clearForm() {
    $('#id').val('');
    $('#cid').val('');
    $('#code').val('');
    $('#name').val('');
    $('#duration').val('');
    $('#note').val('');
    $('#staff').val('');
    $('#uploadedAvatar').attr('src','../images/profiles/0.jp');
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
      processing: true,
      searching : false,
      paging: false,
      bDestroy : true,
      order: [],
      ajax: {
        url: '../data/query_notification_queue.php',
        type: 'POST',
        data : {qid : 1, uid : $('#navLog').val(), 
          pid : $('#pid').val()}
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,8],
        }]
    });
  }