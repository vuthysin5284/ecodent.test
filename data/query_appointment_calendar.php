<?php
  include_once ('../inc/config.php');
  $uid = $_GET['uid'];
  $LOG = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid' LIMIT 1"));
  $SQL = "SELECT `a`.`id`, `cust_fname`,c.cust_code, `appo_datetime`, `appo_duration`, `appo_note`, `a`.`staff_id`, `appo_status`, `s`.`staff_fname` 
  FROM `tbl_appointment` AS `a` 
  INNER JOIN `tbl_customer` AS `c` ON (`a`.`cust_id` = `c`.`id`) 
  INNER JOIN `tbl_staff` AS `s` ON (`s`.`id` = `a`.`staff_id`) 
  WHERE (`appo_status` BETWEEN 1 AND 3) AND `cust_status` = 1";
  $QUERY = mysqli_query($CON, $SQL);
  $appo_data = array();
  if (mysqli_num_rows($QUERY) > 0) {
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      if ($LOG['staff_position_id'] == 1 || $LOG['staff_position_id'] == 2 || $uid == $ROW['staff_id']) {
        $d = $ROW['appo_duration'];
        if($ROW['appo_status'] == 1){ $c = 'Primary';}
        else if($ROW['appo_status'] == 2){$c = 'Danger';}
        else if($ROW['appo_status'] == 3){$c = 'Info';} 

        if ($d == 1) { $duration = '+15 minutes'; }
        else if ($d == 2) { $duration = '+30 minutes'; }
        else if ($d == 3) { $duration = '+45 minutes'; }
        else if ($d == 4) { $duration = '+60 minutes'; }
        else if ($d == 5) { $duration = '+90 minutes'; }
        else if ($d == 6) { $duration = '+120 minutes'; }
        else if ($d == 7) { $duration = '+150 minutes'; }
        else { $duration = '+180 minutes'; }
        $data['id'] = $ROW['id'];
        $data['groupId'] = $ROW['cust_code'];
        $data['title'] = $ROW['cust_fname'];
        $data['start'] = date("Y-m-d H:i:s", strtotime($ROW['appo_datetime']));
        $data['end'] = date("Y-m-d H:i:s", strtotime($ROW['appo_datetime'].' '.$duration));
        $data['extendedProps'] = array('calendar' => $c,'doctor'=>$ROW["staff_fname"],'description'=>$ROW["appo_note"]);
        array_push($appo_data, $data);
      }
    }
  }
  echo json_encode($appo_data);
?>