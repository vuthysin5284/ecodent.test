
  $(document).on('submit', '#loginForm', function(e) {
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
      url : "../data/query_login.php",
      data : formData,
      cache : false,
      contentType : false,
      processData : false,
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if (status == 'true') {
          dbBackup();
          window.location.href = "index.php";
        } else {
          location.href = "login.php";
        }
      }
    });
  });

  function dbBackup() {
    $.ajax({
      url : '../data/query_login.php',
      type : 'POST',
      data : { qid : 2 }
    });
  }