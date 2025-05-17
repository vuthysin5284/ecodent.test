
  $(document).ready(function() {
    getDataTable();
  });

  $(document).on('click', '.deleteBtn', function() {
    var table = $('#dataTable').DataTable();
    var id = $(this).data('id');
    if (confirm("Are you sure that you want to delete this invoice? ")) {
      $.ajax({
        url: "../data/query_patient_invoice.php",
        data: { qid: 0, id: id, 
          pid : $('#pid').val()},
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            $('#dataTable').DataTable().ajax.reload();
            alertText('Invoice has been deleted from database!', 'primary');
          } else {
            alert(data);
            return;
          }
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
      dom: '<"d-flex justify-content-between"<"d-flex justify-content-start justify-content-md-end align-items-baseline mb-0"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-0"lB>><""f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
      language : {
        sLengthMenu : "_MENU_",
        search : "",
        searchPlaceholder : "Search",
      },
      buttons: [{
          className: 'btn btn-primary ms-2',
          text: 'All',
          action: function (e, dt, node, config) { _status = 'ALL'; _table.ajax.reload(); }
        },{
          className: 'btn btn-primary',
          text: 'Pending',
          action: function (e, dt, node, config) { _status = '1'; _table.ajax.reload(); }
        },{
          className: 'btn btn-primary',
          text: 'Paid',
          action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
        },{
          className: 'btn btn-primary',
          text: 'Credit Noted',
          action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
        },{
        extend: 'collection',
        className: 'dropdown-toggle btn btn-label-secondary shadow-none',
        text: '<i class="bx bx-export me-2"></i>Export',
        buttons: [
          { extend: 'print', text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: {columns:[0,1,2,3,4]} },
          { extend: 'excelHtml5', text: '<i class="bx bx-file me-2"></i>Excel', className: "dropdown-item", exportOptions: {columns:[0,1,2,3,4]} },
          { extend: 'copyHtml5', text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: {columns:[0,1,2,3,4]} },
          { extend: 'colvis', text: '<i class="bx bx-grid me-2"></i>Column', className: "dropdown-item" },
        ]
      }],
      pageLength: 25,
      serverSide: true,
      processing: true,
      searching : false,
      paging: true,
      order: [],
      ajax: {
        url: '../data/query_patient_invoice.php',
        type: 'POST',
        data : {
          qid : 1,
          cid : $('#cid').val(), 
          pid : $('#pid').val()
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3,4,5],
        }],
      drawCallback : function(settings) {
        var grandtotal = '<strong>$ ' + settings.json.grandtotal + '</strong>';
        $('#grandTotal').html(grandtotal);
      }
    }); 
  }