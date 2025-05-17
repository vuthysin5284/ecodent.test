  let _table = null;
  $(document).ready(function() {
    var start = moment();
    var end = moment();
    cb(start, end);
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        ranges: {
          'Today': [moment(), moment(), 'Today'],
          'This Week': [moment().startOf('week'), moment().endOf('week')],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'This Year': [moment().startOf('year'), moment().endOf('year')],
        }
    }, cb);
    getDataTable();
  });
  
  $(document).on('click', '#btnFilter', function() {
    // getDataTable();
    _table.ajax.reload();
  });
   
  function cb(start, end) {
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
  }
  
  function getDataTable() {
    _table = new $('#dataTable').DataTable({  
      fnCreatedRow: function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },
      drawCallback: function() {
          $('[data-bs-toggle="tooltip"]').tooltip();
          $('.detail-control').click(function () {
            $(this).find("i").toggleClass("fa-plus-circle fa-minus-circle");
        });
      },dom: '<"d-flex justify-content-between"<"d-flex justify-content-start justify-content-md-end align-items-baseline mb-0"<"dt-action-buttons d-flex align-items-start align-items-md-center justify-content-sm-center mb-0"lB>><""f>>t<"row mx-2"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
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
        text: 'Doctor',
        action: function (e, dt, node, config) { _status = '1'; _table.ajax.reload(); }
      },{
        className: 'btn btn-primary',
        text: 'Patient',
        action: function (e, dt, node, config) { _status = '0'; _table.ajax.reload(); }
      },{
        extend: 'collection',
        className: 'dropdown-toggle btn btn-label-secondary shadow-none',
        text: '<i class="bx me-2"></i> Payment',
        buttons: [
          { text: 'ALL', action: function (e, dt, node, config) { _num_code = 'ALL'; _tableservice.ajax.reload(); } },
          { text: 'Bank', action: function (e, dt, node, config) { _num_code = 'Bank'; _tableservice.ajax.reload(); } },
          { text: 'Cash', action: function (e, dt, node, config) { _num_code = 'Cash'; _tableservice.ajax.reload(); }} 
        ]
      },{
        extend: 'collection',
        className: 'dropdown-toggle btn btn-label-secondary shadow-none',
        text: '<i class="bx bx-export"></i>Export',
        buttons: [
          { extend: 'print', text: '<i class="bx bx-printer me-2"></i>Print', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
          { extend: 'excelHtml5', text: '<i class="bx bx-file me-2"></i>Excel', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
          { extend: 'copyHtml5', text: '<i class="bx bx-copy me-2"></i>Copy', className: "dropdown-item", exportOptions: {columns:[0,2,3,4,5,6,7]} },
          { extend: 'colvis', text: '<i class="bx bx-grid me-2"></i>Column', className: "dropdown-item" },
        ]
      }],
      pageLength : 25,
      serverSide : true,
      processing : true,
      searching : false,
      paging : true,
      bDestroy : true,
      order: [],
      ajax: { 
        url: '../data/query_invoice_completed.php',
        type: 'POST',
        data : function(d) {
          d.qid   = 4;
          d.date  = $('#date').val();
          d.uid   = $('#navLog').val(); 
          d.pid   = $('#pid').val();
        }
      },
      columns : [ 
      {  "searchable": false, sortable:false,"sWidth": '10px',
        "fnCreatedCell"	: function (nTd, sData, oData, iRow, iCol) {
            $(nTd).html('<center><div class="text-center"><a class="tip detail-control" style="font-size: 15px;"><i class="fa fa-plus-circle"></i></a></div></center>');
        }
      }],
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3,4,5,6,7],
        }]
    });


    $('#dataTable tbody').on('click', 'tr td .detail-control', function () {
      var tr = $(this).closest('tr')[0];
      var receive_id = tr.children[1].textContent;
      if($(tr).hasClass('details')){
          tr.classList.remove('details');
          $(tr).next().remove();
      }
      else{
          var datas = null;
          tr.classList.add('details');
          $(tr).after(
              "<tr>" +
                  "<td colspan='11'>" +
                      "<div class='table-responsive'>" +
                          "<table id='receive-item"+receive_id+"' class='table table-bordered table-hover table-striped reports-table receive-item' width='100%'>" +
                              "<thead>" +
                                  "<tr>" +
                                      "<th style='background-color: white; color: black;'>#</th>" +
                                      "<th class='text-center'>Payment ID</th>" +
                                      "<th class='text-center'>Invoice No</th>" +
                                      "<th class='text-left'>Patient</th>" +
                                      "<th class='text-left'>Dentist</th>" +
                                      "<th class='text-right'>Amount ($)</th>" + 
                                      "<th class='text-center'>Method</th>" + 
                                      "<th class='text-left'>Created By</th>" + 
                                      "<th class='text-center'>Date</th>" +  
                                  "</tr>" + 
                              "</thead>" +
                              "<tfoot>" +
                                  "<th colspan='5' style='text-align: right;'></th>" +
                                  "<th style='text-align: center;'></th>" + 
                                  "<th colspan='3' style='text-align: right;'></th>" +
                              "</tfoot>" +
                          "</table>" +
                      "</div>" +
                  "</td>" +
          "</tr>");
          $('#receive-item'+receive_id).dataTable({
            bLengthChange: 0,
            serverSide: true,
            processing: true,
            searching : false,
            paging    : false,
            bDestroy  : true,  
            "bFilter"       : false,
            "bInfo"         : false,
            "bPaginate"     : false,
            order: [], 
            drawCallback: function() {
                $('[data-bs-toggle="tooltip"]').tooltip();
              //   $('.detail-control-item').click(function () {
              //     $(this).find("i").toggleClass("fa-plus-circle fa-minus-circle");
              // });
            },
            ajax: { 
              url: '../data/query_invoice_completed.php',
              type: 'POST',
              data : {
                qid : 5, 
                head_id : receive_id, 
                pid : $('#pid').val()
              }
            },
            // columns : [{  
            //   "searchable": false, sortable:false,"sWidth": '10px',
            //   "fnCreatedCell"	: function (nTd, sData, oData, iRow, iCol) {
            //       $(nTd).html('<center><div class="text-center"><a class="tip detail-control-item" style="font-size: 15px;"><i class="fa fa-plus-circle"></i></a></div></center>');
            //   }
            // }],
            aoColumnDefs: [{
                bSortable: false,
                aTargets: [0,1,2,3,4,5,6,7,8],
              }]
          });
        }
    }); 

    // $('#dataTable tbody').on('click', 'tr td .detail-control-item', function () {
    //   var tr = $(this).closest('tr')[0];
    //   var invoice_id = tr.children[1].textContent;
    //   if($(tr).hasClass('details-item')){
    //       tr.classList.remove('details-item');
    //       $(tr).next().remove();
    //   }
    //   else{ 
    //       tr.classList.add('details-item');
    //       $(tr).after(
    //         "<tr>" +
    //             "<td colspan='11'>" +
    //                 "<div class='table-responsive'>" +
    //                     "<table id='invoice-item"+invoice_id+"' class='table table-bordered table-hover table-striped reports-table invoice-item' width='100%'>" +
    //                         "<thead>" +
    //                             "<tr>" +
    //                                 "<th style='background-color: white; color: black;'>#</th>" + 
    //                                 "<th class='text-center'>Treatment Description</th>" +
    //                                 "<th class='text-left'>Tooth No.</th>" +
    //                                 "<th class='text-left'>Qty</th>" +
    //                                 "<th class='text-right'>Price</th>" + 
    //                                 "<th class='text-center'>Disc</th>" + 
    //                                 "<th class='text-left'>Total</th>" +  
    //                             "</tr>" + 
    //                         "</thead>" + 
    //                     "</table>" +
    //                 "</div>" +
    //             "</td>" +
    //     "</tr>"); 
    //     // 
    //     $('#invoice-item'+invoice_id).dataTable({
    //       bLengthChange: 0,
    //       serverSide: true,
    //       processing: true,
    //       searching : false,
    //       paging    : false,
    //       bDestroy  : true,  
    //       "bFilter"       : false,
    //       "bInfo"         : false,
    //       "bPaginate"     : false,
    //       order: [], 
    //       drawCallback: function() {
    //           $('[data-bs-toggle="tooltip"]').tooltip(); 
    //       },
    //       ajax: { 
    //         url: '../data/query_invoice_completed.php',
    //         type: 'POST',
    //         data : {
    //           qid : 5, 
    //           head_id : invoice_id, 
    //           pid : $('#pid').val()
    //         }
    //       }, 
    //       aoColumnDefs: [{
    //           bSortable: false,
    //           aTargets: [0,1,2,3,4,5,6],
    //         }]
    //     }); 
    //   }
    // });
     
  }