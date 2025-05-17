<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];
  if ($qid == 0) { getNotifyNum(); }
  else if ($qid == 1) { searchData(); }
  else if ($qid == 2) { changeLang(); }
  else if ($qid == 3) { fnNotification(); }
  else if ($qid == 4) { fnShortcut(); }
  else if ($qid == 5) { get_menu_badge(); }
  else if ($qid == 6) { get_submenu_badge(); }
  else {}
  
  function getNotifyNum() {
    global $CON;
    $start = date('Y-m-d').' 00:00:00';
    $end = date('Y-m-d').' 23:59:59';
    $SQL = "SELECT COUNT(*) AS `notification` FROM `tbl_notification` AS `n` INNER JOIN `tbl_customer` AS `c` ON (`n`.`cust_id` = `c`.`id`) WHERE (`n`.`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` > 0 AND `notify_status` = 1"; 
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo $ROW['notification'];
  }

  function get_menu_badge() {
    global $CON;
    $start = date('Y-m-d').' 00:00:00';
    $end = date('Y-m-d').' 23:59:59';
    $APPO = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_appointment` AS `a` INNER JOIN `tbl_customer` AS `c` ON (`a`.`cust_id` = `c`.`id`) WHERE (`a`.`appo_datetime` BETWEEN '$start' AND '$end') AND `cust_status` = 1 AND `appo_status` = 1"));
    $QUEU = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_queue` AS `q` INNER JOIN `tbl_customer` AS `c` ON (`q`.`cust_id` = `c`.`id`) WHERE (`q`.`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` = 1 AND `queue_status` = 1 "));
    $SERV = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_serving` AS `s` INNER JOIN `tbl_customer` AS `c` ON (`s`.`cust_id` = `c`.`id`) WHERE (`s`.`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` = 1 AND `serve_status` = 1"));
    $notification = $APPO['n'] + $QUEU['n'] + $SERV['n'];
    $DRAF = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` = 1"));
    $PEND = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` = 2"));
    $invoice = $DRAF['n'] + $PEND['n'];
    $data = array('notification' => $notification, 'invoice' => $invoice);
    echo json_encode($data);
  }

  function get_submenu_badge() {
    global $CON;
    $start = date('Y-m-d').' 00:00:00';
    $end = date('Y-m-d').' 23:59:59';
    $APPO = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_appointment` AS `a` INNER JOIN `tbl_customer` AS `c` ON (`a`.`cust_id` = `c`.`id`) WHERE (`a`.`appo_datetime` BETWEEN '$start' AND '$end') AND `cust_status` = 1 AND `appo_status` = 1"));
    $QUEU = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_queue` AS `q` INNER JOIN `tbl_customer` AS `c` ON (`q`.`cust_id` = `c`.`id`) WHERE (`q`.`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` = 1 AND `queue_status` = 1 "));
    $SERV = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_serving` AS `s` INNER JOIN `tbl_customer` AS `c` ON (`s`.`cust_id` = `c`.`id`) WHERE (`s`.`timestamp` BETWEEN '$start' AND '$end') AND `cust_status` = 1 AND `serve_status` = 1"));
    $DRAF = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` = 1"));
    $PEND = mysqli_fetch_assoc(mysqli_query($CON, "SELECT COUNT(*) AS `n` FROM `tbl_invoice_patient` WHERE (`timestamp` BETWEEN '$start' AND '$end') AND `inv_status` = 2"));
    $data = array('appointment' => $APPO['n'], 'queue' => $QUEU['n'], 'serving' => $SERV['n'], 'draft' => $DRAF['n'], 'pending' => $PEND['n']);
    echo json_encode($data);
  }

  function fnShortcut() {
    global $CON;
    $uid = $_POST['uid'];
    $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
    $title = array('Administration', 'Dentist', 'Patient', 'Appointment', 'Queue', 'Serving', 'Draft', 'Pending', 'Prescription');
    $subtitle = array('Dashboard', 'Dashboard', 'Registration', 'Notification', 'Notification', 'Notification', 'Invoice', 'Invoice', 'Patient');
    $icon = array('bxs-dashboard', 'bxs-dashboard','bx-user', 'bx-calendar', 'bx-calendar-check', 'bx-calendar-heart', 'bx-receipt', 'bx-wallet', 'bx-capsule');
    $url = array('dashboard.php?pgid=1', 'dashboard_dentist.php?pgid=37', 'patient_list.php?pgid=7', 'notification_appointment.php?pgid=2', 'notification_queue.php?pgid=3', 'notification_serving.php?pgid=4', 'invoice_draft.php?pgid=9', 'invoice_pending.php?pgid=10','notification_diagnosis.php?pgid=39');
    if ($ROW['staff_position_id'] == 1) {
      $shortcut = array(0, 2, 3, 4, 5, 6, 7, 8);
    } else if ($ROW['staff_position_id'] == 2) {
      $shortcut = array(0, 2, 3, 4, 6, 7);
    } else if ($ROW['staff_position_id'] == 3) {
      $shortcut = array(1, 3, 4, 5);
    }
    $o .= '<div class="row row-bordered overflow-visible g-0">';
    foreach ($shortcut as $i) {
      $o .= '<div class="dropdown-shortcuts-item col-6">';
      $o .= '<span class="dropdown-shortcuts-icon bg-label-secondary rounded-circle mb-2">';
      $o .= '<i class="bx '.$icon[$i].' fs-4"></i>';
      $o .= '</span>';
      $o .= '<a href="'.$url[$i].'" class="stretched-link">'.$title[$i].'</a>';
      $o .= '<small class="text-muted mb-0">'.$subtitle[$i].'</small>';
      $o .= '</div>';
    }
    $o .= '</div>';
    echo $o;
  }

  function timeCalc($time, $tense = 'ago') {
    $period = array('y', 'm', 'd', 'h', 'min', 'sec');
    $now = new DateTime('now');
    $time = new DateTime($time);
    $diff = $now -> diff($time) -> format('%y %m %d %h %i %s');
    $diff = explode(' ', $diff);
    $diff = array_combine($period, $diff);
    $diff = array_filter($diff);
    $period = key($diff);
    $value = current($diff);
    if (!$value) {
      $period = '';
      $tense = '';
      $value = 'Just Now';
    } else {
      if ($period == 'sec' && $value <= 59) {
        $period = '';
        $tense = '';
        $value = 'Just Now';
      }
      if ($period == 'd' && $value >= 7) {
        $period = 'w';
        $value = floor($value/7);
      }
    }
    return "$value$period $tense";
  }

  function fnNotification() {
    global $CON;
    $uid = $_POST['uid'];
    $start = date('Y-m-d').' 00:00:00';
    $end = date('Y-m-d').' 23:59:59';
    $SQL = "SELECT `n`.*, `cust_fname`, `cust_image` FROM `tbl_notification` AS `n` INNER JOIN `tbl_customer` AS `c` ON (`n`.`cust_id` = `c`.`id`)  WHERE (`n`.`timestamp` BETWEEN '$start' AND '$end') AND `notify_status` = 1 AND `cust_status` > 0 ORDER BY `n`.`timestamp` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    if (mysqli_num_rows($QUERY) > 0) {
      $o = '';
      $i = 0;
      while ($ROW = mysqli_fetch_assoc($QUERY)) {
        if ($i < 5) {
          $custId = 'P-'. sprintf('%05d', $ROW['cust_id']);
          $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
          $timestamp = $ROW['timestamp'];
          if ($ROW['notify_id'] == 1) {
            $notification = 'Patient is waiting in queue.';
            $url = '../page/notification_queue.php?pgid=3';
          } else if ($ROW['notify_id'] == 2) {
            $notification = 'Patient is waiting in treatment room.';
            $url = '../page/notification_serving.php?pgid=4';
          } else if ($ROW['notify_id'] == 3) {
            $notification = 'Dentist created a draft invoice for this patient.';
            $url = '../page/invoice_draft.php?pgid=9';
          } else if ($ROW['notify_id'] == 4) {
            $notification = 'Dentist created a pending invoice for this patient.';
            $url = '../page/invoice_pending.php?pgid=10';
          }else {
            $notification = '';
          }
          $o .= '<a href="'.$url.'">';
          $o .= '<li class="list-group-item list-group-item-action dropdown-notifications-item">';
          $o .= '<div class="d-flex">';
          $o .= '<div class="flex-shrink-0 me-3">';
          $o .= '<div class="avatar">';
          $o .= '<img src="../images/profiles/'.$folder.''.$ROW['cust_image'].'.jpg" alt="user-avatar" class="w-px-40 h-auto rounded-circle">';
          $o .= '</div>';
          $o .= '</div>';
          $o .= '<div class="flex-grow-1">';
          $o .= '<h6 class="mb-1">'.$ROW['cust_fname'].'</h6>';
          $o .= '<p class="mb-0">'.$notification.'</p>';
          $o .= '<small class="text-muted">'.timeCalc($timestamp).'</small>';
          $o .= '</div>';
          $o .= '</div>';
          $o .= '</li>';
          $o .= '</a>';
        }
        $i = $i + 1;
      }
    } else {
      $o = '<li class="list-group-item list-group-item-action dropdown-notifications-item">No notification to display!</li>';
    }
    echo $o;
  }

  function changeLang() {
    global $CON;
    $lang = $_POST['language'];
    $uid = $_POST['uid'];
    $QUERY = mysqli_query($CON, "UPDATE `tbl_staff` SET `user_lang` = '$lang' WHERE `id` = '$uid'");
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function searchData() {
    global $CON;
    $output = '';
    $str = $_POST['str'];
    $SQL = "SELECT `cust_image`, `cust_fname`, `cust_gender`, `cust_dob`";
    // if (strlen($str) == 11) {
      $prefix = substr($str, 0, 3);
      if ($prefix == 'CUS') {
        $qr = trim($str, 'CUS');
        $SQL .= ", `id` AS `cid`, `cust_code` FROM `tbl_customer` AS `c` WHERE `cust_code` LIKE '%".$qr."%' AND `cust_status` = 1 LIMIT 1";
      } else if ($prefix == 'INV') {
        $qr = trim($str, 'INV');
        $SQL .= ", `i`.`id` AS `invid`, `cust_id` AS `cid`, `inv_code` FROM `tbl_invoice_patient` AS `i` INNER JOIN `tbl_customer` AS `c` ON (`i`.`cust_id` = `c`.`id`) WHERE `inv_code` LIKE '%".$qr."%' LIMIT 1";
      } else if ($prefix == 'PRE') {
        $qr = trim($str, 'PRE');
        $SQL .= ", `d`.`id` AS `pid`, `cust_id` AS `cid` FROM `tbl_diagnosis` AS `d` INNER JOIN `tbl_customer` AS `c` ON (`d`.`cust_id` = `c`.`id`) WHERE `pres_code` LIKE '%".$qr."%' LIMIT 1";
      } else {
      $SQL .= ", `id` AS `cid`, `cust_code` FROM `tbl_customer` AS `c` WHERE (`c`.`id` LIKE '%".$str."%' OR `cust_fname` LIKE '%".$str."%' OR `cust_contact` LIKE '%".$str."%') AND `cust_status` = 1 ORDER BY `cust_fname` ASC LIMIT 10";
    }
    $QUERY = mysqli_query($CON, $SQL);
    if (mysqli_num_rows($QUERY) > 0) {
      while ($ROW = mysqli_fetch_assoc($QUERY)) {
        $custId = 'P-'. sprintf('%05d', $ROW['cid']);
        $folder = ($ROW['cust_image'] == '0') ? '' : $custId.'/';
        $data = '(P-'.sprintf('%05d', $ROW['cid']).') '.$ROW['cust_fname'];
        $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
        $gender = ($ROW['cust_gender'] == 0) ? '<i class="bx bx-female-sign"></i> Female' : '<i class="bx bx-male-sign"></i> Male';
          if ($prefix == 'CUS') {
            $output .= '<a href="../patients/patient_chart.php?pgid=7&pid='.$ROW['cust_code'].'&cid='.$ROW['cust_code'].'&apid=0" class="list-group-item list-group-item-action data-item p-3">';
          } else if ($prefix == 'INV') {
            $output .= '<a href="invoice_payment.php?pgid=7&icode='.$ROW['inv_code'].'" class="list-group-item list-group-item-action data-item p-3">'; 
          } else if ($prefix == 'PRE') {
            $output .= '<a href="print_prescription.php?pgid=7" class="list-group-item list-group-item-action data-item p-3">';
          } else {
          $output .= '<a href="../patients/patient_chart.php?pgid=7&pid='.$ROW['cust_code'].'&cid='.$ROW['cust_code'].'&apid=0" class="list-group-item list-group-item-action data-item p-3">';
        } 
        $output .= '<div class="row text-nowrap">';
        $output .= '<div class="col-3"><img src="../images/profiles/'.$folder.''.$ROW['cust_image'].'.jpg" alt="img" class="d-block rounded" height="50" width="50" id="uploadedAvatar"/></div>';
        $output .= '<div class="col-6"><h5 class="mb-2">'.$data.'</h6>'.$gender.'&nbsp; - &nbsp;'.$age.' Years </div>';
        $output .= '</div>';
        $output .= '</a>';
      }
    } else {
      $output = 'No result is matched!';
    }
    echo $output;
  }
?>