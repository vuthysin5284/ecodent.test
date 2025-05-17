  let _table = null;
  let _status = "ALL";
  $(function() {
    var start = moment();
    var end = moment();
    cb(start, end);
    $('#reportrange').daterangepicker({
        startDate : start,
        endDate   : end,
        ranges: {
          'Today'     : [moment(), moment(), 'Today'],
          'This Week' : [moment().startOf('week'), moment().endOf('week')],
          'This Month': [moment().startOf('month'), moment().endOf('month')],
          'This Year' : [moment().startOf('year'), moment().endOf('year')],
        }
    }, cb);
    getDataTable();
    getDataReport('This Month');
  });
  
  $(document).on('click', '#btnFilter', function() {
    // getDataTable();  
    
    getDataReport(); 
    _table.ajax.reload();

  });
  
  $(document).on('click', '.deleteBtn', function() { 
    var id = $(this).data('id');
    if (confirm("Are you sure that want to delete this invoice? ")) {
      $.ajax({
        url: "../data/query_patient_invoice.php",
        data: { qid: 0, id: id },
        type: "POST",
        success: function(data) {
          var json = JSON.parse(data);
          var status = json.status;
          if (status == 'success') {
            _table.ajax.reload();
            alertText('Invoice has been deleted from database!', 'primary');
          } else {
            alert(data);
            return;
          }
        }
      });
    } else { return null; }
  });
  
  function cb(start, end) { 
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
  }
  
  function getDataTable() {
    _table = $('#dataTable').DataTable({  
      fnCreatedRow: function(nRow, aData, iDataIndex) {
        $(nRow).attr('id', aData[0]);
      },
      drawCallback: function() {
          $('[data-bs-toggle="tooltip"]').tooltip();
          $('.detail-control').click(function () {
            $(this).find("i").toggleClass("fa-plus-circle fa-minus-circle");
        });
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
        text: 'Bank',
        action: function (e, dt, node, config) { _status = 'BANK'; _table.ajax.reload(); }
      },{
        className: 'btn btn-primary',
        text: 'Cash',
        action: function (e, dt, node, config) { _status = 'CASH'; _table.ajax.reload(); }
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
      "processing": true,
      "serverSide": true,
      "bDestroy": true,
      "bJQueryUI": true,
      searching : false,
      paging: true, 
      order: [], 
      ajax: { 
        url: '../data/query_invoice_completed.php',
        type: 'POST',
        data: function (d) { 
              d.qid   = 1;
              d.menthod = _status;
              d.date  = $('#date').val();
              d.uid   = $('#navLog').val(); 
              d.pid   = $('#pid').val(); 
        },
        // data : {
        //   qid : 4,
        //   date : _date,
        //   uid : $('#navLog').val(), 
        //   pid : $('#pid').val()
        // }
      },
      aoColumnDefs: [
        { bSortable: false, aTargets: [1] },
        { bVisible: false, aTargets: [] },
      ],
      drawCallback : function(settings) {
        // $('#grandTotal').html(settings.json.grandTotal);
        // $('#remainTotal').html(settings.json.remainTotal);
        $('#paidTotal').html(settings.json.paidTotal);
      }
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
            ajax: { 
              url: '../data/query_invoice_completed.php',
              type: 'POST',
              data : {
                qid : 5, 
                head_id : receive_id, 
                pid : $('#pid').val()
              }
            },
            aoColumnDefs: [{
                bSortable: false,
                aTargets: [0,1,2,3,4,5,6,7,8],
              }]
          });
        }
    }); 

     
  }
  function getDataReport(str) {
    $.ajax({
      url : '../data/query_invoice_completed.php',
      type : 'POST',
      data : {
        qid : 6,
        date : $('#date').val(),
        str : $('#str').val(),
        lang : $('#navLang').val(),
      },
      success : function(data) {
        $('#dataReport').html(data);
      }
    });
  }
  //
  function getDataPaymentListTable() {
    $('#dataPaymentListTable').DataTable({  
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
      bDestroy : true,
      order: [],
      ajax: { 
        url: '../data/query_invoice_completed.php',
        type: 'POST',
        data : {
          qid : 2,
          date : $('#date').val(),
          uid : $('#navLog').val(), 
          pid : $('#pid').val()
        }
      },
      aoColumnDefs: [{
          bSortable: false,
          aTargets: [0,1,2,3,4,5,6,7,8],
        }]
    });
  }  
  //
  $(document).on('click', '#create', function() {
    $('.modal-dialog form').attr('id','addForm');   
    clearForm();
  });
  function clearForm() {
    $('#id').val(0);
    $('#cid').val('');
    $('#code').val(''); 
    $('#entry_date').val(setNow());
    $('#post_date').val(setNow());
    $('#remark').val('');
    
    getDataPaymentListTable(); 
  } 
// submit form
  $(document).on('submit', '#addForm', function(e) {
    e.preventDefault();
    var uid = $('#navLog').val();
    var paid_id = $("input[name='paid_id[]']:checked");
    var formData = new FormData(this);
    formData.append('qid', 3); 
    formData.append('uid', uid); 
    if(paid_id.length>0){ 
      if (confirm("Are you sure that you want to proceed receive payment? ")) { 
        $.ajax({
          type : "POST",
          enctype : "multipart/form-data",
          url : "../data/query_invoice_completed.php",
          data : formData,
          cache : false,
          contentType : false,
          processData : false,
          success : function(data) {
            var json = JSON.parse(data);
            var status = json.status; 
            if(status == true) {              
              $('#Modal').modal('hide');
              $('#dataTable').DataTable().draw();
              alertText('The receive payment item been created!', 'primary');
            } else { alert('Failed'); }
          }
        });  
      }else { return null; }
    }
    else{
      alert('Please select atleast one item to create receive payment !');
      return false;
    } 
    
  });