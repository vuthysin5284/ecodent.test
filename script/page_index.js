  $(document).ready(function() {
    getImage();
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var formData = new FormData(this);
    formData.append('qid', 1);
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
      url : "../data/query_background_image.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'true') {              
          getImage();
          $('#progressbar').html(0 + '%');
          $('#progressbar').width(0 + '%');
          $('#files').val('');
        }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure want to delete this menu? ")) {
      $.ajax({
        url: "../data/query_background_image.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            getImage();
            alertText('Staff has been deleted database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  function getImage() {
    $.ajax({
      url : '../data/query_background_image.php',
      type : 'POST',
      data : {qid : 2},
      success : function(data) {
        $('#imageData').html(data);
      }
    });
  }