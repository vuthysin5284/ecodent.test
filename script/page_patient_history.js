
  $(document).ready(function() {
    getDataTable();
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var cid = $('#cid').val();
    var formData = new FormData(this);
    formData.append('qid', 2);
    formData.append('cid', cid);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_medical_history.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'True') {   
          getDataTable();           
          alertText('Medical history has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  function getDataTable() {
    var cid = $('#cid').val();
    $.ajax({
      url : '../data/query_medical_history.php',
      type : 'POST',
      data : { qid : 1, cid : cid},
      success : function (data) {
        $('#dataTable').html(data);
      }
    });
  }