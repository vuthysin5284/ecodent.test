
  $(document).ready(function() {
    $("#permission").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9,]/g, '')); });
    getDataTable();
  });
  
  $(document).on('click', '#create', function() {
    clearForm();
    $('.modal-dialog form').attr('id','addForm');
    $.ajax({
      url : "../data/query_staff_position.php",
      data : { qid : 5 },
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        var id = parseInt(json.id) + 1;
        $('#id').val(id);
      }
    }); 
  });
  
  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('qid', 3);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_staff_position.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'True') {              
          $('#Modal').modal('hide');
          $('#dataTable').DataTable().draw();
          alertText('Position has been saved to database!', 'primary');
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
      url : "../data/query_staff_position.php",
      data : {qid : 2, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        $('#id').val(json.id);
        $('#name').val(json.staff_position);
        $('#permission').val(json.default_permission);
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
      url : "../data/query_staff_position.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'True') {
          clearForm();
          $('#Modal').modal('hide');
          $('#dataTable').DataTable().draw();
          alertText('Position has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });
  
  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this position?")) {
      $.ajax({
        url: "../data/query_staff_position.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'True') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Position has been saved to database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });
  
  function clearForm() {
    $('#id').val('');
    $('#name').val('');
    $('#permission').val('');
  }
  /* Function getDataTable */
  function getDataTable() {
    $('#dataTable').DataTable({  
      fnCreatedRow: function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },
      drawCallback: function() {
        $('[data-bs-toggle="tooltip"]').tooltip();
      },
      pageLength: 25,
      paging : false,
      serverSide: true,
      processing: true,
      searching : false,
      paging: false,
      order: [],
      ajax: {
        url: '../data/query_staff_position.php',
        type: 'POST',
        data : {qid : 1, 
          pid : $('#pid').val()}
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3],
        }]
    });
  }