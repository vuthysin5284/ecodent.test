  
  $(document).ready(function() {
    navShortcut();
    getNotifyNum();
    get_menu_badge();
    get_submenu_badge();
  });

  $(document).on('click', '#nav-notification', function () {
    navNotification();
  });

  // $('#navSearch').change(function(e){
  //   e.preventDefault();
  //   var str = $(this).val();
  //   if(str == ''){
  //     $('#custData').hide(); 
  //     $('#navSearch').val(null);
  //   }
  //   else {
  //     navData(str);
  //   }
  // });

  $('#navSearch').keyup(function(e){
    e.preventDefault();
    var str = $(this).val();
    if(str == ''){
      $('#custData').hide(); 
      $('#navSearch').val(null);
    }
    else {
      navData(str);
    }
  });

  function navData(str) {
    $.ajax({
      url : '../data/query_nav_search.php',
      type : 'POST',
      data : {
        qid : 1,
        str : str
      },
      success : function(data) {
        $('#custData').html(data).show();
      }
    });
  }

  function navShortcut() {
    $.ajax({
      url : '../data/query_nav_search.php',
      type : 'POST',
      data : {
        qid : 4,
        uid : $('#navLog').val(), 
        pid : $('#pid').val()
      },
      success : function(data) {
        $('#nav-shortcut').html(data);
      }
    });
  }

  function navNotification() {
    $.ajax({
      url : '../data/query_nav_search.php',
      type : 'POST',
      data : {
        qid : 3, 
        uid : $('#navLog').val(), 
        pid : $('#pid').val()
      },
      success : function(data) {
        $('#nav-notification-detail').html(data);
      }
    });
  }

  $('#lang_kh').on('click', function() {
    $.ajax({
      url : '../data/query_nav_search.php',
      type : 'POST',
      data : {
        qid : 2, 
        uid : $('#navLog').val(), 
        pid : $('#pid').val(),
        language : 2,
      },
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'true') {
          window.location.href="logout.php";
        }
      }
    });
  });

  $('#lang_en').on('click', function() {
    $.ajax({
      url : '../data/query_nav_search.php',
      type : 'POST',
      data : {
        qid : 2, 
        uid : $('#navLog').val(),
        language : 1, 
        pid : $('#pid').val()
      },
      success : function(data) {
        var json = JSON.parse(data);
        var status = json.status;
        if(status == 'true') {
          window.location.href="logout.php";
        }
      }
    });
  });

  function getNotifyNum() {
    $.ajax({
      url : '../data/query_nav_search.php',
      type : 'POST',
      data : {qid : 0},
      success : function(data) {
        $('#badge-notify').text(data);
      }
    });
  }

  function get_menu_badge() {
    $.ajax({
      url : '../data/query_nav_search.php',
      type : 'POST',
      data : {qid : 5},
      success : function(data) {
        var json = JSON.parse(data);
        var not = parseInt(json.notification);
        var inv = parseInt(json.invoice);
        if (not != 0) { $('#badge-notification').text(not); }
        if (inv != 0) { $('#badge-invoice').text(inv); }
      }
    });
  }

  function get_submenu_badge() {
    $.ajax({
      url : '../data/query_nav_search.php',
      type : 'POST',
      data : {qid : 6},
      success : function(data) {
        var json = JSON.parse(data);
        var appo = parseInt(json.appointment);
        var queu = parseInt(json.queue);
        var serv = parseInt(json.serving);
        var draf = parseInt(json.draft);
        var pend = parseInt(json.pending);
        if (appo != 0) { $('#badge-appointment').text(appo); }
        if (queu != 0) { $('#badge-queue').text(queu); }
        if (serv != 0) { $('#badge-serving').text(serv); }
        if (draf != 0) { $('#badge-draft-invoice').text(draf); }
        if (pend != 0) { $('#badge-pending-invoice').text(pend); }
      }
    });
  }