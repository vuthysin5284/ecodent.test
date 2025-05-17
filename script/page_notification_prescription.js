
  $(document).ready(function() {
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    clearForm();
    $('.modal-dialog form').attr('id','addForm');
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    // var pgid = $('#pgid').val();
    var formData = new FormData(this);
    formData.append('qid', 3);
    formData.append('uid', $('#navLog').val());
    formData.append('cid', $('#cid').val()); 
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_patient_prescription.php",
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
          alertText('Medicine has been added to prescription table!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });
  
  $('#medicine').keyup(function() {
    var str = $(this).val();
    if (str == '') {
      $('#medSearch').hide();
    } else {
      $('#medSearch').show();
      medData(str);
    }
  });
  
  $(document).on('click', '.med-data', function() {
    var medicine = $(this).text();
    $('#medicine').val(medicine);
    $('#medSearch').hide();
  })
  
  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this medicine? ")) {
      $.ajax({
        url: "../data/query_patient_prescription.php",
        data: { qid : 0, id: id , 
          pid : $('#pid').val()},
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Medicine has been deleted from database!', 'primary');
          } else {
            alert(data);
            return;
          }
        }
      });
    } else { return null; }
  });
  
  function medData(str) {
    $.ajax({
      url : '../data/query_patient_prescription.php',
      type : 'POST',
      data : {qid : 2, str : str},
      success : function(data) {
        $('#medSearch').html(data);
      }
    });
  }
  
  function clearForm() {
    $('#medicine').val('');
    $('#category').val(4);
    $('#morning').val(1);
    $('#afternoon').val(1);
    $('#evening').val(1);
    $('#duration').val(5);
    $('#instruction').val(0);
    $('#medSearch').hide();
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
      processing: false,
      searching : false,
      paging: false,
      info : false,
      order: [],
      ajax: {
        url: '../data/query_patient_prescription.php',
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
          aTargets: [],
        }]
    }); 
  }