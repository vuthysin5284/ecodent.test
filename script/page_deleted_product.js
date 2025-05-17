
  $(document).ready(function() {
    getDataTable();
  });

  $(document).on('click', '.recoveryBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to recover this deleted product? ")) {
      $.ajax({
        url: "../data/query_deleted_product.php",
        data: { qid : 2, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Product has been restored to product list!', 'primary');
          } else { alert(data); return; }
        }
      });
    } else { return null; }
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to permanently delete this product? ")) {
      $.ajax({
        url: "../data/query_deleted_product.php",
        data: { qid : 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Product has been deleted from database!', 'primary');
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
      paging: true,
      order: [],
      ajax: {
        url: '../data/query_deleted_product.php',
        type: 'POST',
        data : { qid : 1, 
          pid : $('#pid').val() }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [1,7],
        }]
    });
  }