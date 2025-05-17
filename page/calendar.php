<?php
  $page = 'Calendar';
  session_start();
  $log = $_SESSION['uid'];
  $lang = 1;
  // include_once('../inc/session.php');
  include_once('../inc/config.php');
 
  $text_form = 'Appointment Information';
  $text_form_description = '* Patient\'s ID, Image, and Qr Code is auto-fill when you select a patient!';
  $m1 = 'Patient ID';
  $m2 = 'Qr Code';
  $m3 = 'Full Name';
  $m4 = 'Date & Time';
  $m5 = 'Duration';
  $m6 = 'Dentist';
  $m7 = 'Remark';
  $th2 = 'Image';
  $th3 = 'Patient Info';
  $th4 = 'Date & Time';
  $th5 = 'Duration';
  $th6 = 'Dentist';
  $th7 = 'Remark';
  $th8 = 'Created By';
  $th9 = 'Action';
?>
<!DOCTYPE html>
<html
  lang="en"
  class="light-style layout-menu-fixed"
  dir="ltr"
  data-theme="theme-default"
  data-assets-path="../assets/"
  data-template="vertical-menu-template-free"
>
  <head>
    <?php 
    if ($lang == 1) {
      $page_category = 'Reservation';
      $page_title = 'Calendar';
    } else {
      $page_category = 'ការរំលឹក';
      $page_title = 'ប្រតិទិន';
    }
      include_once('../inc/header.php');
      include_once('../inc/setting.php');
    ?>  
  </head>
  <body>
    <div class="layout-wrapper layout-content-navbar">
      <div class="layout-container">
        <?php include_once('../inc/navigation_menu.php'); ?>
        <div class="layout-page">
          <?php include_once('../inc/navbar.php'); ?>
          <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
              <div class="d-flex justify-content-between">
                <div class="me-auto">
                  <!-- <h4 class="fw-bold"> -->
                    <!-- <span class="text-muted fw-light">< ?php echo $page_category; ?>   -->
                  <!-- </h4> -->
                </div>
                <div class="py-2">
                  <?php 
                  // if(in_array($_GET['pgid'], explode(',',$_SESSION['user_add_perm']))){ ?>
                    <button id="btnListView"  class="btn btn-label-primary d-none">
                      <span class="tf-icons d-none d-sm-block"><i class="bx bx-list-ol"></i>&nbsp; List View</span>
                      <i class="bx bx-list d-block d-sm-none"></i>
                    </button>
                    <button type="button" id="create" class="btn btn-primary d-none" data-bs-toggle="modal" data-bs-target="#Modal">
                      <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; New</span>
                      <i class="bx bx bx-plus d-block d-sm-none"></i>
                    </button>
                  <?php
                //  }
                  ?>
                </div>
              </div>
              <!-- form appointment  -->
              <!-- <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body"> -->
                <div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 800px;" tabindex="-1" id="appointmentOffcanvas" 
                  aria-labelledby="appointmentOffcanvasLabel"> 
                  <div class="offcanvas-header">
                      <h5 class="offcanvas-title" id="appointmentOffcanvasLabel"><?php echo $text_form; ?></h5>
                      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                  </div>
                  <div class="offcanvas-body">
                    <form  id="addForm" method="POST" enctype="multipart/form-data"> 
                      <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                        <img src="../images/profiles/0.jpg" alt="img" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                      </div>
                      <div class="row mb-3">
                        <?php echo inpHidden('id', '','2'); ?>
                        <?php echo inpText($m1, 'cid', '','2'); ?>
                        <?php echo inpText($m2, 'code', '','2'); ?>
                          <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_description; ?></div>
                        </div>
                      <div class="mb-3 row">
                        <div class="col">
                          <label for="name" class="form-label"><?php echo $m3; ?></label>
                          <select class="form-select" id="name" name="name"></select>
                          <input type="text" class="form-control" id="showName" readonly />
                        </div>
                      </div>
                      <div class="row mb-3"><?php echo inpDateTime($m4, 'datetime'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m5, 'duration', 'SELECT * FROM `tbl_treatment_duration` ORDER BY `id` ASC', 'trmt_duration'); ?></div>
                      <div class="row mb-3"><?php echo selectData($m6, 'sid', 'SELECT * FROM `tbl_staff` WHERE `id` > 1 AND `staff_status` = 1 ORDER BY `id` ASC', 'staff_fname'); ?></div>
                      <div class="row mb-3"><?php echo inpText($m7, 'note', 'Ex: Orthodontics', 3); ?></div>
                      <div class="offcanvas-footer" style="margin-bottom:50px;margin-top:50px;">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form> 
                </div>
              </div> 
                    <!-- </div>
                    <!-- </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form>
                </div>
              </div> -->
              <!-- Calendar -->
              <div class="card">
                <div class="card-body">
                  <div id="calendar" class="px-4"></div>
                </div>
              </div>
            </div>
            <?php include_once('../inc/footage.php'); ?> 
          </div>
        </div>
      </div>
    </div>
  </body>
  <foot>
    <?php include_once('../inc/footer.php'); ?>
    <script src="../script/page_main.js"></script> 
    <script>
      window.onload = function(){
        var activeMenu = document.getElementById("Appointments");
        activeMenu.classList.add("active");
        // activeMenu.classList.add("open");
        // var activeSubMenu = document.getElementById("Appointments");
        // activeSubMenu.classList.add("active");
      };

      document.addEventListener('DOMContentLoaded', function() {
        var calendarsColor = {
          Primary : 'primary',
          Success : 'success',
          Danger  : 'danger',
          Warning : 'warning',
          Info    : 'info'
        };
        var calendarEl = document.getElementById('calendar'); 
        var calendar = new FullCalendar.Calendar(calendarEl, { 
          initialView: 'dayGridMonth',
          editable: true,
          aspectRatio: 3, 
          nowIndicator: true,  
          dayMaxEventRows: 4, 
          headerToolbar: {
            start: 'prev,today,next',
            center : 'title',
            end: 'btnAppointment, dayGridMonth,timeGridWeek,timeGridDay,ListView' //listMonth
          },customButtons: {
            btnAppointment: {
              text: '+ New Appointment',
              click: function() {
                // $('#create').trigger('click');
    // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('appointmentOffcanvas'));
    offcanvas.show();
              }
            },ListView: { 
              className: 'btn btn-primary ms-2',
              text: 'List View',
              click: function() {
                // $('#btnListView').trigger('click');
                window.location.href = "appointment.php";
              }
            }
          },
          eventClassNames: function ({ event: calendarEvent }) {
            const colorName = calendarsColor[calendarEvent._def.extendedProps.calendar];    
            return ['fc-event-' + colorName ];
          },  
          eventDidMount:function(calEvent) { 
            //
            const eventEl = calEvent.el
            const event = calEvent.event
            eventEl.ondblclick = () => {  
              window.location.href = '../patients/patient_chart.php?pgid=7&pid='+event.groupId+'&cid=' + event.groupId+'&apid='+event.id; 
            }   
            $('.fc-event-primary, .fc-event-success, .fc-event-danger, .fc-event-warning, .fc-event-info ').each(function(){
                var content = $(this).data('content');
                $(this).attr('title',event.extendedProps.doctor+" :: "+  event.extendedProps.description); 
                $(this).tooltip({
                  tooltipClass: 'event-tooltip',
                  content: content,
                }); 
            });
            
          },  
          events : '../data/query_appointment_calendar.php?uid=<?php echo $log; ?>',
        });
        calendar.render();
      });

      $(document).ready(function() { 
        $('#name').select2({
          ajax : {
            url : '../data/query_select_patient.php',
            dataType : 'json',
            data : function (params) {
              var query = {
                search : params.term,
                type : 'cust_search',
              };
              return query;
            },
            processResults : function (data) {
              return {
                results : data
              }
            }
          },
          cache : false,
          theme: "bootstrap-5",
          dropdownParent: "#Modal",
          closeOnSelect: true,
          placeholder: '--- select patient ---',
        });
      });
      $(document).on('click', '#create', function() {
        clearForm();
        $('.modal-dialog form').attr('id','addForm');
        $('#name').next(".select2-container").show();
        $('#name').prop('required', true);
        $('#showName').hide();
      });
      function clearForm() {
        $('#id').val(0);
        $('#cid').val('');
        $('#code').val('');
        $('#name').val(null).trigger('change');
        $('#showName').val('');
        $('#datetime').val(setNow());
        $('#duration').val(2);
        $('#sid').val(2);
        $('#note').val('');
        $('#uploadedAvatar').attr('src','../images/profiles/0.jpg');
      }
      // onchange patient 
      $(document).on('change', '#name', function(){
        var name = $('#name').val();
        $.ajax({
          url : '../data/query_notification_appointment.php',
          type : 'POST',
          data : {qid : 2, name : name},
          success : function(data) {
            var json = JSON.parse(data);
            var cid = 'P-' + padZero(json.id, 5);
            var img = json.cust_image;
            var folder = '';
            if (img != '0') { folder = cid + '/'; }
            $('#cid').val(cid);
            $('#code').val(json.cust_code);
            $('#uploadedAvatar').attr('src','../images/profiles/' + folder + img + '.jpg');
          }
        });
      });
      // submit form
      $(document).on('submit', '#addForm', function(e) {
        e.preventDefault();
        var uid = $('#navLog').val();
        var formData = new FormData(this);
        formData.append('qid', 4);
        formData.append('uid', uid);
        $.ajax({
          type : "POST",
          enctype : "multipart/form-data",
          url : "../data/query_notification_appointment.php",
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
              alertText('Appointment has been saved to database!', 'primary');
            } else { alert('Failed'); }
          }
        });
      });
    </script>
  </foot>
</html>
 