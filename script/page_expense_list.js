
  $(document).ready(function() {
    $("#amount").on('input', function(e) { $(this).val($(this).val().replace(/[^0-9.]/g, '')); });
    $('#categ').change(function () {
      var str = $(this).val();
      $.ajax({
        url : '../data/query_expense_list.php',
        data : { qid : 6, str : str },
        type : 'POST',
        success: function(data) {
          $('#supplier').html(data);
        }
      });
    });
    getDataTable();
  });

  $(document).on('click', '#create', function() {
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('expenseOffcanvas'));
    offcanvas.show();
    // clearForm();
    // $('.modal-dialog form').attr('id','addForm');
    // $('#code').val(randQr(8));
    // $.ajax({
    //   url : "../data/query_expense_list.php",
    //   data : {qid : 5},
    //   type : 'POST',
    //   success : function(data) {
    //     var json = JSON.parse(data);
    //     var id = json.id;
    //     if (id == null) { id = 1 } else {id = parseInt(id) + 1; }
    //     var sid = 'EXP-' + padZero(id,5);
    //     $('#id').val(sid);
    //   }
    // }); 
  });

  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var formData = new FormData(this);
    formData.append("qid", 3);
    formData.append("uid", uid);
    $.ajax({
      type : "POST",
      enctype : "multipart/form-data",
      url : "../data/query_expense_list.php",
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
          alertText('Expense has been saved to database!', 'primary');
        } else { alert('Failed'); }
      }
    });
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure want to delete this menu? ")) {
      $.ajax({
        url: '../data/query_expense_list.php',
        data: { qid : 0, id: id, 
          pid : $('#pid').val() },
        type: 'POST',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Expense Invoice has been deleted database!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

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
        url: '../data/query_expense_list.php',
        type: 'POST',
        data : {qid : 1, 
          pid : $('#pid').val()}
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,7],
        }]
    });
  }

  function clearForm() {
    $('#upload').val('');
    $('#id').val('');
    $('#code').val('');
    $('#description').val('');
    $('#categ').val('');
    $('#suppid').val('');
    $('#amount').val('');
  }