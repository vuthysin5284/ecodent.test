<?php
  include_once ('../inc/config.php');
  $qid = $_GET['qid'];
  if ($qid == 1) { fnCheckIn(); } else {}

  function fnCheckIn() {
    global $CON;
    $ccode = $_GET['ccode'];
    $uid = $_GET['uid'];
    $date = date("Y-m-d");
    $start = $date.' 00:00:00';
		$end = $date.' 23:59:59';
    $QUEUE = mysqli_query($CON, "SELECT `q`.`id` FROM `tbl_queue` AS `q` INNER JOIN `tbl_customer` AS `c` ON (`q`.`cust_id` = `c`.`id`) WHERE (`cust_code` = '$ccode' OR `cust_fname` = '$ccode') AND `queue_status` = 1");
    if (mysqli_num_rows($QUEUE) > 0) {
      $data = array('status' => 'inQueue');
    } else {
      $SQL = "SELECT `a`.`id` FROM `tbl_appointment` AS `a` INNER JOIN `tbl_customer` AS `c` ON (`a`.`cust_id` = `c`.`id`) WHERE (`a`.`timestamp` BETWEEN '$start' AND '$end') AND (`cust_code` = '$ccode' OR `cust_fname` = '$ccode') AND `appo_status` = 1";
      $QUERY = mysqli_query($CON, $SQL);
      if (mysqli_num_rows($QUERY) > 0) {
        while ($ROW = mysqli_fetch_assoc($QUERY)) {
          $id = $ROW['id'];
          $APPO = mysqli_fetch_assoc(mysqli_query($CON, "SELECT * FROM `tbl_appointment` WHERE `id` = '$id'"));
          $appoId = $APPO['id'];
          $custId = $APPO['cust_id'];
          $staffId = $APPO['staff_id'];
          $userId = $APPO['user_id'];
          $duration = $APPO['appo_duration'];
          $note = $APPO['appo_note'];
          $queueStatus = 1;
          $UPDATE = mysqli_query($CON, "UPDATE `tbl_appointment` SET `appo_status` = 2 WHERE id = '$appoId'");
          $TRANS = mysqli_query($CON, "INSERT INTO `tbl_queue` (`appo_id`, `cust_id`, `staff_id`, `user_id`, `queue_duration`, `queue_note`, `queue_status`) VALUES ('$appoId', '$custId', '$staffId', '$userId', '$duration', '$note', '$queueStatus')");
          $NOTIFICATION = mysqli_query($CON, "INSERT INTO `tbl_notification` (`cust_id`, `notify_id`, `user_id`) VALUES ('$custId', 1, '$staffId')");
        }
        $data = array('status' => 'success');
      } else {
        $SQL = "SELECT `id` FROM `tbl_customer` WHERE (`cust_code` = '$ccode' OR `cust_fname` = '$ccode') LIMIT 1";
        $QUERY = mysqli_query($CON, $SQL);
        $ROW = mysqli_fetch_assoc($QUERY);
        $custId = $ROW['id'];
        $sid = 1;
        $appo_id = 1;
        $queueStatus = 1;
        $queue_duration = 1;
        $TRANS = mysqli_query($CON, "INSERT INTO `tbl_queue` (`appo_id`, `cust_id`, `staff_id`, `queue_duration`,`user_id`,  `queue_status`) VALUES ('$appo_id', '$custId', '$sid', '$queue_duration', '$uid', '$queueStatus')");
        $NOTIFICATION = mysqli_query($CON, "INSERT INTO `tbl_notification` (`cust_id`, `notify_id`, `user_id`) VALUES ('$custId', 1, 1)");
        $data = array('status' => 'success');
      }
    }
    echo json_encode($data);
  }

  ?>