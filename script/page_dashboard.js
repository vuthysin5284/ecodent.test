  
  $(document).ready(function() {
    var start = moment().startOf('month');
    var end = moment().endOf('month');
    var str = 'This Month';
    cb(start, end, str);
    $('#name').select2({ theme: "bootstrap-5", dropdownParent: "#Modal", placeholder: $(this).data( 'placeholder'), closeOnSelect: true });
    $('#reportrange').daterangepicker({
        startDate: start,
        endDate: end,
        title : str,
        ranges: {
          'Today': [moment(), moment(), 'Today'],
          'This Week': [moment().startOf('week'), moment().endOf('week'), 'This Week'],
          'This Month': [moment().startOf('month'), moment().endOf('month'), 'This Month'],
          'This Year': [moment().startOf('year'), moment().endOf('year'), 'This Year'],
        }
    }, cb);
      getDataReport('This Month');
      getTreatmentStatistics();
      getDataTransaction();
      getDataStockAlert();
  });

  $(document).on('click', '#btnFilter', function() {
    getDataReport();
    getDataTable();
    getTreatmentStatistics();
    getDataTransaction();
    getDataStockAlert();
  });

  function getDataReport(str) {
    $.ajax({
      url : '../data/query_dashboard.php',
      type : 'POST',
      data : {
        qid : 2,
        date : $('#date').val(),
        str : $('#str').val(),
        lang : $('#navLang').val()
      },
      success : function(data) {
        $('#dataReport').html(data);
      }
    });
  }

  function getTreatmentStatistics() {
    $.ajax({
      url : '../data/query_dashboard.php',
      type : 'POST',
      data : {
        qid : 3,
        str : $('#str').val(),
        date : $('#date').val(),
      },
      success : function(data) {
        $('#treatmentStatisctics').html(data);
      }
    });
  }

  function getDataTransaction() {
    $.ajax({
      url : '../data/query_dashboard.php',
      type : 'POST',
      data : {
        qid : 4,
        date : $('#date').val(),
      },
      success : function(data) {
        $('#dataTransaction').html(data);
      }
    });
  }

  function getDataStockAlert() {
    $.ajax({
      url : '../data/query_dashboard.php',
      type : 'POST',
      data : {
        qid : 5,
        date : $('#date').val(),
      },
      success : function(data) {
        $('#dataStockAlert').html(data);
      }
    });
  }

  function cb(start, end, str) {
    $('#date').val(start.format('YYYY / MM / DD') + '   -   ' + end.format('YYYY / MM / DD'));
    $('#str').val(str);
  }
