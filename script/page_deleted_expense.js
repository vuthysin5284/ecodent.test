
  /* Main Function */
  $(document).ready(function() {
    getDataTable();
  });

  /* Click Recovery Button */
  $(document).on('click', '.recoveryBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to recover this deleted expense? ")) {
      $.ajax({
        url: "../data/query_deleted_expense.php",
        data: { qid : 2, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Expense has been restored to expense list!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to permanently delete this expense? ")) {
      $.ajax({
        url: "../data/query_deleted_expense.php",
        data: { qid : 0, id: id },
        type: 'POST',
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Expense has been deleted from database!', 'primary');
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
      dom: '<"d-flex justify-content-between"<""l><""f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      language : {
        sLengthMenu : '_MENU_',
        search : '',
        searchPlaceholder : 'Search',
      },
      pageLength: 25,
      serverSide: true,
      processing: true,
      searching : true,
      paging: true,
      order: [],
      ajax: {
        url: '../data/query_deleted_expense.php',
        type: 'POST',
        data : { qid : 1, 
          pid : $('#pid').val()}
      },
      aoColumnDefs: [{
        bSortable: false,
        aTargets: [5],
      }]
    });
  }