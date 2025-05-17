
  $(document).ready(function() {
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    clearForm();
    $('.modal-dialog form').attr('id','addForm');
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var cid = $('#cid').val();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 3);
    formData.append('cid', cid);
    formData.append('uid', uid);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_patient_files_category.php",
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
          alertText('File category has been saved to database!', 'primary');
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
      url : "../data/query_patient_files_category.php",
      data : {qid : 2, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        $('#title').val(json.files_description);
        $('#id').val(json.id);
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
      url : "../data/query_patient_files_category.php",
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
          $('#dataTable').DataTable().draw();
          alertText('File category has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure want to delete this menu? ")) {
      $.ajax({
        url: "../data/query_patient_files_category.php",
        data: { qid : 0, id: id },
        type: 'POST',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('File category has been deleted from database!', 'primary');
          } else {
            alert(data);
            return;
          }
        }
      });
    } else { return null; }
  });

  function clearForm() {
    $('#id').val(0);
    $('#title').val('');
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
      order: [],
      ajax: {
        url: '../data/query_patient_files_category.php',
        type: 'POST',
        data : {
          qid : 1,
          cid : $('#cid').val(),
          uid : $('#navLog').val(), 
          pid : $('#pid').val()
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3],
        }]
    }); 
  }