
  $(document).ready(function() {
    getImage();
  });

  $(document).on('click', '#create', function() {
    clearForm();
    $('.modal-dialog form').attr('id','addForm');
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var cid = $('#cid').val();
    var uid = $('#navLog').val();
    var fid = $('#fid').val();
    var formData = new FormData(this);
    formData.append('qid', 1);
    formData.append('cid', cid);
    formData.append('fid', fid);
    formData.append('uid', uid);
    $.ajax({
      xhr : function() {
        var xhr = new window.XMLHttpRequest();
        xhr.upload.addEventListener("progress", function (evt) {
          if(evt.lengthComputable) {
            var percentComplete = evt.loaded / evt.total;
            percentComplete = parseInt(percentComplete * 100);
            $('#progressbar').html(percentComplete + '%');
            $('#progressbar').width(percentComplete + '%');
          }
        }, false);
        return xhr;
      },
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_patient_file.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if (status == 'true') {
          clearForm();
          $('#progressbar').html(0 + '%');
          $('#progressbar').width(0 + '%');
          $('#Modal').modal('hide');
          getImage();
          alertText('Files category has been saved to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '.deleteImage', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this image? ")) {
      $.ajax({
        url: "../data/query_patient_file.php",
        data: { qid : 0, id: id },
        type: "post",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            getImage();
            alertText('Image file has been deleted from database!', 'primary');
          } else {
            alert(data); return;
          }
        }
      });
    } else { return null; }
  });

  function clearForm() {
    $('#files').val('');
  }

  function getImage() {
    $.ajax({
      url : '../data/query_patient_file.php',
      type : 'POST',
      data : {
        qid : 2,
        cid : $('#cid').val(),
        fid : $('#fid').val(),
        uid : $('#navLog').val(), 
        pid : $('#pid').val()
      },
      success : function(data) {
        $('#imageData').html(data);
      }
    });
  }