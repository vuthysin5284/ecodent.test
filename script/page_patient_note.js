  
  var note;
  $(document).ready(function() {
    ClassicEditor
      .create( document.querySelector( '#note' ) )
      .then( editor => {
          note = editor;
      } )
      .catch( error => {
              console.error( error );
      } );
      $('#tooth').select2({ theme: "bootstrap-5", dropdownParent: "#Modal", placeholder: $(this).data( 'placeholder'), });
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    $('.modal-dialog form').attr('id','addForm');
    clearForm();
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var cid = $('#cid').val();
    var formData = new FormData(this);
    formData.append('qid', 3);
    formData.append('cid', cid);
    formData.append('uid', uid);
    $.ajax({
      type : 'POST',
      enctype : 'multipart/form-data',
      url : '../data/query_patient_clinical_note.php',
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
          alertText('Clinical notes has been saved to database!', 'primary');
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
      url : "../data/query_patient_clinical_note.php",
      data : {qid : 2, id : id, 
        pid : $('#pid').val()},
      type : 'POST',
      success : function(data) {
        var json = JSON.parse(data);
        var tid = json.tooth_id;
        var tooth = tid.split(',');
        $('#id').val(json.id);
        $('#datetime').val(json.timestamp);
        $('#tooth').val(tooth).trigger('change');
        $('#dentist').val(json.user_id);
        note.setData(json.clinical_note);
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
      url : "../data/query_patient_clinical_note.php",
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
          alertText('Clinical note has been updated to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this note? ")) {
      $.ajax({
        url: "../data/query_patient_clinical_note.php",
        data: { qid: 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Clinical note has been deleted from database!', 'primary');
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
    var now = new Date();
    var timezoneOffset = 7 * 60;
    var offsetDateTime = new Date(now.getTime() + timezoneOffset * 60000);
    var formattedDateTime = offsetDateTime.toISOString().slice(0,16).replace("T", " ");
    $('#datetime').val(formattedDateTime);
    $('#tooth').val(null).trigger('change');
    $('#dentist').val(2);
    note.setData('');
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
        url: '../data/query_patient_clinical_note.php',
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
        }],
    }); 
  }