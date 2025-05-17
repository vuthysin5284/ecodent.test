<?php
  include_once('../inc/session.php');
  include_once('../inc/config.php');
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
      $page_title = 'User Account';
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
                  <h4 class="fw-bold py-3 mb-4">
                    <span class="text-muted fw-light">Staffs / </span><?php echo $page_title; ?>
                  </h4>
                </div>
                <div class="p-2">
                  <button type="button" id="create" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#Modal">
                    <span class="tf-icons d-none d-sm-block"><i class="bx bx bx-plus"></i>&nbsp; New User Account</span>
                            <i class="bx bx bx-plus d-block d-sm-none"></i>
                  </button>
                </div>
              </div>
              <!-- Modal -->
              <div class="modal fade" id="Modal" data-bs-backdrop="static" tabindex="-1">
                <div class="modal-dialog">
                  <form class="modal-content" id="addForm" method="POST" enctype="multipart/form-data"> 
                    <div class="modal-header">
                      <h5 class="modal-title" id="backDropModalTitle">User Account</h5>
                      <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Close"
                      ></button>
                    </div>
                    <div class="modal-body">
                      <div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">
                        <img src="../images/profiles/0.jpg" alt="img" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />
                      </div>
                      <div class="row mb-3"> 
                        <?php echo inpHidden('id','2'); ?>
                        <?php echo inpHidden('permission', '2'); ?>
                        <?php echo inpText('Staff ID', 'sid', '','2'); ?>
                        <?php echo inpText('Qr Code', 'code', '','2'); ?>
                        <div id="defaultFormControlHelp" class="form-text">  * Staff's ID, Image, and Qr Code is auto-filled! </div>
                      </div>
                      <div class="mb-3 row">
                        <?php echo select2Data('Staff', 'name', 'SELECT * FROM `tbl_staff` ORDER BY `staff_fname` ASC', 'staff_fname'); ?>
                      </div>
                      <div class="row mb-3">
                        <?php echo inpText('Nickname', 'nickname', 'Dr. Kimhuon'); ?>
                        <div id="defaultFormControlHelp" class="form-text">  * Nickname will display on system to represent user identity. </div>
                      </div>
                      <div class="row mb-3">  <?php echo inpText('Username', 'username', 'Username'); ?> </div>
                      <div class="row mb-3">  <?php echo inpPwd('Password', 'password'); ?> </div>
                    </div>
                    <div class="modal-footer">
                      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                        Close
                      </button>
                      <button type="submit" class="btn btn-primary">Save</button>
                    </div>
                  </form>
                </div>
              </div>
              <!-- User Dropdown -->
              <div class="card">
                <div class="table-responsive text-nowrap p-4 me-md-0 me-sm-1 me-4">
                  <table class="table table-bordered pt-2" id="dataTable">
                    <thead>
                      <tr>
                        <th class="text-center">#</th>
                        <th class="text-center">Image</th>
                        <th class="text-left">Staff Info</th>
                        <th class="text-center">Username</th>
                        <th class="text-center">Password</th>
                        <th class="text-center">Action</th>
                      </tr>
                    </thead>
                    <tbody class="table-border-bottom-0"></tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php include_once('../inc/footer.php'); ?> 
    <script type="text/javascript">
      function padZero (str, max) {
        str = str.toString();
        return str.length < max ? padZero("0" + str, max) : str;
      }
      function alertText(text, color) {
        var style = color + " w-auto text-center text-nowrap";
        $.bootstrapGrowl(text, {
          type : style,
          offset : { from:"top", amount: 100 },
          align : "center",
          delay : 3000,
          allow_dismiss: true,
        });
      }
      function clearForm() {
        $('#id').val('');
        $('#sid').val('');
        $('#code').val('');
        $('#name').val(null).trigger('change');
        $('#nickname').val('');
        $('#username').val('');
        $('#password').val('');
        $('#uploadedAvatar').attr('src','../images/profiles/0.jpg');
      }
      /* Query Table */
      $(document).ready(function() {
        $('#name').select2( { theme: "bootstrap-5", placeholder: $( this ).data( 'placeholder' ), dropdownParent: "#Modal", } );
        $('#dataTable').DataTable({  
          "fnCreatedRow": function(nRow, aData, iDataIndex) {
            $(nRow).attr('id', aData[0]);
          },  
          'pageLength': 25,
          'serverSide': 'true',
          'processing': 'true',
          'paging': 'true',
          'order': [],
          'ajax': {
            'url': '../data/query_user_account.php',
            'type': 'post',
            'data' : {"qid" : 1}
          },
          "aoColumnDefs": [{
              "bSortable": false,
              "aTargets": [0,3],
            }]
        });
      });
      /* Select Staff */
      $(document).on('change', '#name', function(){
        var name = $('#name').val();
        $.ajax({
          url : '../data/query_user_account.php',
          type : 'POST',
          data : {qid : 2, name : name},
          success : function(data) {
            var json = JSON.parse(data);
            var sid = 'S-' + padZero(json.id, 5);
            var img = json.staff_image;
            var folder = '';
            if (img != '0') { folder = sid + '/'; }
            $('#sid').val(sid);
            $('#code').val(json.staff_code);
            $('#permission').val(json.default_permission);
            $('#uploadedAvatar').attr('src','../images/profiles/' + folder + img + '.jpg');
          }
        });
      });
      /* Click Create Button */
      $(document).on('click', '#create', function() {
        clearForm();
        $('.modal-dialog form').attr('id','addForm');
      });
      /* Click Create Button */
      $('#username').blur(function() {
        var str = $(this).val();
        $.ajax({
          url : '../data/query_user_account.php',
          type : 'POST',
          data : {qid : 4, str : str},
          success : function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if(status == 'True') {
              alert('This username is taken! Please choose another one.');
              $('#username').val('');
              $('#username').focus();
            }
          }
        });
      });
      /* Submit addForm */
      $(document).on('submit', '#addForm', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append('qid', 3);
        $.ajax({
          type : "POST",
          enctype : "multipart/form-data",
          url : "../data/query_user_account.php",
          data : formData,
          cache : false,
          contentType : false,
          processData : false,
          success : function(data) {
            var json = JSON.parse(data);
            var status = json.status;
            if(status == 'True') {              
              $('#Modal').modal('hide');
              $('#dataTable').DataTable().draw();
              alertText('User has been saved to database!', 'primary');
            } else { alert('Failed'); }
          }
        });
      });
    </script>
    <script type="text/javascript">
      window.onload = function(){
          var activeMenu = document.getElementById("Staff");
          activeMenu.classList.add("active");
          var activeSubMenu = document.getElementById("User Account");
          activeSubMenu.classList.add("active");
      };
    </script>
  </body>
</html>