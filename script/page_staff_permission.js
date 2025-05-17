
$(document).ready(function() {
    permissionRows();
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('qid', 2);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_user_permission.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'True') {              
          alertText('User permission has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '#editBtn', function(){
    var id = $('#sid').val();
    $('#Modal').modal('show');
    $('.modal-dialog form').attr('id','editForm');
    $.ajax({
      url : "../data/query_user_permission.php",
      data : {qid : 3, id : id},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        $('#id').val(json.id);
        $('#username').val(json.username);
        $('#password').val('');
      }
    });
  });

  $(document).on('submit', '#editForm', function (e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append("qid", 4);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_user_permission.php",
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
          alertText('User has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  function clearForm() {
    $('#id').val('');
    $('#nickname').val('');
    $('#username').val('');
    $('#password').val('');
  }

  function permissionRows() {
    var sid = $('#sid').val();
    var lang = $('#navLang').val();
    $.ajax({
      url : '../data/query_user_permission.php',
      type : 'POST',
      data : {qid : 1, sid : sid, lang : lang},
      success : function (data) {
        $('#permissionRows').html(data);
      }
    });
  }