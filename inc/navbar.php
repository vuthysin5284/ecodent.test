  <?php
    session_start();
    $NAVUSER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `s`.`id`, `staff_fname`, `staff_image`, `staff_position_id`, `staff_position` FROM `tbl_staff` AS `s` INNER JOIN `tbl_staff_position` AS `sp` ON (`s`.`staff_position_id` = `sp`.`id`) WHERE `s`.`id`= '$log' LIMIT 1"));
    $nav_user = $NAVUSER['staff_fname'];
    $nav_staff_id = $NAVUSER['id'];
    $nav_user_image = $NAVUSER['staff_image'];
    $nav_staff_position = $NAVUSER['staff_position'];
    $nav_staff_position_id = $NAVUSER['staff_position_id'];
    $nav_staff_full_id = 'S-'. sprintf('%05d', $nav_staff_id);
    // get data setting
    $rowt = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM tbl_system_setting where id ='".$_SESSION["system_id"]."'")); 
    
    $_SESSION['rating'] = $rowt['rating'];
    //
    if ($nav_user_image == 0) {
      $nav_staff_image = $nav_user_image.'.jpg';
    } else {
      $nav_staff_image = $nav_staff_full_id.'/'.$nav_user_image.'.jpg';
    }
    if ($lang == 1) {
      $text_nav_search = 'Search...';
      $text_nav_setting = 'Change Password';
      $text_nav_logout = 'Log Out';
    } else {
      $text_nav_search = 'ស្វែងរក...';
      $text_nav_setting = 'ប្ដូរលេខសម្ងាត់';
      $text_nav_logout = 'ចាកចេញ';
    }
  ?>

  <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
      <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
        <i class="bx bx-menu bx-sm"></i>
      </a>
    </div>
    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
      <div class="navbar-nav align-items-center">
        <div class="nav-item d-flex align-items-center">
          <i class="bx bx-search fs-4 lh-0"></i>
          <input type="text" id="navSearch" name="navSearch" class="form-control border-0 shadow-none" placeholder="<?php echo $text_nav_search; ?>" aria-label="Search..."/>
        </div>
      </div>

      <ul class="navbar-nav flex-row align-items-center ms-auto"> 
        <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
          <a href="#" id="createPatient"  data-bs-toggle="dropdown" aria-expanded="false"  style="margin-right:10px">
            <i class="bx bx-user-plus me-1"></i>Patient 
          </a>
        </li>  
        <!-- <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
          <a href="#" id="createAppointment"  data-bs-toggle="dropdown" aria-expanded="false"  style="margin-right:10px">
            <i class="bx bx-time me-1"></i>Appointment
          </a>
        </li> -->

        <li class="nav-item dropdown-language dropdown me-2 me-xl-0">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bx bx-globe bx-sm"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="javascript:void(0);" data-language="kh" id="lang_kh">
                <span class="align-middle">ខ្មែរ</span>
              </a>
            </li>
            <li>
              <a class="dropdown-item" href="javascript:void(0);" data-language="en" id="lang_en">
                <span class="align-middle">English</span>
              </a>
            </li>
          </ul>
        </li>
        <!-- <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="true">
            <i class="bx bx-grid-alt bx-sm"></i>
          </a>
          <div class="dropdown-menu dropdown-menu-end py-0" data-bs-popper="static">
            <div class="dropdown-menu-header border-bottom">
              <div class="dropdown-header d-flex align-items-center py-3">
                <h5 class="text-body mb-0 me-auto">Shortcuts</h5>
                <i class="bx bx-sm bx-grid-alt"></i>
              </div>
            </div>
            <div id="nav-shortcut" class="dropdown-shortcuts-list scrollable-container ps"></div>
          </div>
        </li> -->
        <li id="nav-notification" class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-1">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
            <i class="text-body bx bx-bell bx-sm"></i>
            <span id="badge-notify" class="badge bg-danger rounded-pill badge-notifications">0</span>
          </a>
          <ul class="dropdown-menu dropdown-menu-end py-0">
            <li class="dropdown-menu-header border-bottom">
              <div class="dropdown-header d-flex align-items-center py-3">
                <h5 class="text-body mb-0 me-auto">Notification</h5>
                <i class="bx fs-4 bx-envelope-open"></i>
              </div>
            </li>
            <li class="dropdown-notifications-list scrollable-container">
              <ul class="list-group list-group-flush" id="nav-notification-detail"></ul>
            </li>
          </ul>
        </li>
        <li class="nav-item navbar-dropdown dropdown-user dropdown">
          <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
            <div class="avatar avatar-online">
              <img src="../images/profiles/<?php echo $nav_staff_image; ?>" alt="avatar" class="w-px-40 h-auto rounded-circle">
            </div>
          </a>
          <ul class="dropdown-menu dropdown-menu-end">
            <li>
              <a class="dropdown-item" href="javascript:void(0);">
                <div class="d-flex">
                  <div class="flex-shrink-0 me-3">
                    <div class="avatar avatar-online">
                      <img src="../images/profiles/<?php echo $nav_staff_image; ?>" alt="avatar" class="w-px-40 h-auto rounded-circle">
                    </div>
                  </div>
                  <div class="flex-grow-1">
                    <span class="fw-medium d-block"><?php echo $nav_user; ?></span>
                    <small class="text-muted"><?php echo $nav_staff_position; ?></small>
                  </div>
                </div>
              </a>
            </li>
            <li><div class="dropdown-divider"></div></li>
            <li>
              <a class="dropdown-item" href="setting_privacy.php">
                <i class="bx bx-cog me-2"></i>
                <span class="align-middle"><?php echo $text_nav_setting; ?></span>
              </a>
            </li>
            <li><div class="dropdown-divider"></div></li>
            <li>
              <a class="dropdown-item" href="<?php echo 'logout.php';?>">
                <i class="bx bx-power-off me-2"></i>
                <span class="align-middle"><?php echo $text_nav_logout; ?></span>
              </a>
            </li>
          </ul>
        </li>
      </ul>
    </div>
  </nav>
  <div class="container-xxl"><div id="custData" class="dropdown-menu p-2"></div></div>
  <div class="container">
    <div class="col"><input type="hidden" id="navLog" name="navLog" value="<?php echo $log; ?>" class="form-control" readonly/></div>
    <div class="col"><input type="hidden" id="navLang" name="navLang" value="<?php echo $lang; ?>" class="form-control" readonly/></div>
    <div class="col"><input type="hidden" id="pgid" name="pgid" value="<?php echo $_GET['pgid']; ?>" class="form-control" readonly/></div>
    <div class="col"><input type="hidden" id="business_id" name="business_id" value="<?= $_SESSION['business_id'] ?>" class="form-control" readonly/></div>
    <div class="col"><input type="hidden" id="rating" name="rating" value="<?=$rowt["rating"]?>" class="form-control" readonly/></div>
  </div>
  <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
  <script src="../script/page_navbar.js"></script> 


<div class="offcanvas offcanvas-end" style="--bs-offcanvas-width: 70%;" tabindex="-1" id="rgPatientOffcanvas" 
  aria-labelledby="rgPatientOffcanvasLabel"> 
  <div class="offcanvas-header">
      <h5 class="offcanvas-title" id="rgPatientOffcanvasLabel"><i class="bx bx-user-plus me-2"></i> New Register Patient</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
  </div>
  <div class="offcanvas-body">
  <form  id="addForm" method="POST" enctype="multipart/form-data"> 

    <?php echo inpImage('image', '../images/profiles/0.jpg'); ?>
    <div class="row mb-3">
      <div class="col">
        <label for="id" class="form-label"><?php echo $m1; ?></label>
        <div class="input-group input-group-merge">
          <input type="text" class="form-control" id="id" name="id" />
          <button type="button" class="input-group-text cursor-pointer" id="editPatientID" value='0'><i id='iconPatientID'></i></button>
        </div>   
      </div>
      <?php //echo inpText($m1, 'id', '','1'); ?>
      <?php echo inpText($m2, 'code', '','2'); ?>
      <div id="defaultFormControlHelp" class="form-text"><?php echo $text_form_description; ?></div>
    </div>
    <div class="row mb-3"><?php echo selectData($m3, 'membership', 'SELECT * FROM tbl_membership ORDER BY memb_discount ASC', 'memb_type'); ?></div>
    <div class="row mb-3"><?php echo inpText($m4, 'name', 'Ex: Sun Kimhuon'); ?></div>
    <div class="row mb-3"> 
      <?php echo selectGender($m5, 'gender'); ?>
      <?php echo inpDate($m6, 'dob', ''); ?>
      <?php echo inpNum($m12, 'age', ''); ?>
    </div>
    <div class="row mb-3">
      <?php echo inpText($m7, 'contact', 'Ex: 012 345 678', 0); ?>
      <?php echo inpText($m11, 'email', 'Ex: me@mail.com', 0); ?>
    </div>
    <div class="row mb-3"><?php echo inpText($m8, 'address', 'Ex: Toul Kork, Phnom Penh', 0); ?></div>
    <div class="row mb-3"><?php echo selectData($m9, 'dentist', 'SELECT * FROM `tbl_staff` WHERE `id` > 1 AND `staff_status` = 1 ORDER BY `id` ASC', 'staff_fname'); ?></div>
    <div class="row mb-3"><?php echo inpDateTime($m10, 'datetime'); ?></div> 

    <div class="offcanvas-footer" style="margin-bottom:50px;margin-top:50px;">
      <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
      <button type="submit" class="btn btn-primary">Save</button>
    </div>
  </form> 
  </div>
</div> 


<script>
$(document).on("click", "#createPatient", function () {
   // Show the offcanvas
    const offcanvas = new bootstrap.Offcanvas(document.getElementById('rgPatientOffcanvas'));
    offcanvas.show();

  // $(".modal-dialog form").attr("id", "addForm");
  // clearForm();
  // $("#code").val(randQr(8));
  // $.ajax({
  //   url: "../data/query_patient_list.php",
  //   data: { qid: 5 },
  //   type: "POST",
  //   success: function (data) {
  //     var json = JSON.parse(data);
  //     var id = json.id;
  //     if (id == null) {
  //       id = 1;
  //     } else {
  //       id = parseInt(id) + 1;
  //     }
  //     var sid = "P-" + padZero(id, 5);
  //     $("#id").val(sid);
  //   },
  // });
}); 
</script>