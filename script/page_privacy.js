
  $(document).ready(function() {
    clearForm();
    getUser();
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append('qid', 1);
    formData.append('uid', uid);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_setting_privacy.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'True') { 
          alertText('Password has been saved to database!', 'primary');
          window.location.href = 'index.php';
        } else if (status == 'incPwd') {
          alertText('Password is incorrect, please try again', 'primary');
        } else if (status == 'imcPwd') {
          alertText('Password not matched, please try again!', 'primary');
        } else { 
          alertText('Error!', 'primary'); 
        }
        clearForm();
      }
    });
  });

  function clearForm() {
    $('#username').val('');
    $('#oldpwd').val('');
    $('#newpwd').val('');
    $('#confirmpwd').val('');
  }

  function getUser() {
    var id = $('#navLog').val();
    $.ajax({
      url : '../data/query_setting_privacy.php',
      type : 'POST',
      data : { qid : 2, id : id },
      success : function(data) {
        var json = JSON.parse(data);
        $('#username').val(json.username);
      }
    });
  }