<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow('tbl_treatment_plan'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_treatment_plan` SET `plan_status` = 0 WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == true) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];
    $SQL = "SELECT `tp`.`id`, DATE_ADD(`tp`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `plan_title`, `cust_code`, `cust_fname`, `staff_fname`, 
    `tp`.`user_id`,tp.is_invoice 
    FROM `tbl_treatment_plan` AS `tp` 
    INNER JOIN `tbl_customer` AS `c` ON (`tp`.`cust_id` = `c`.`id`) 
    INNER JOIN `tbl_staff` AS `s` ON (`tp`.`user_id` = `s`.`id`) 

    WHERE `plan_status` = 1 AND `cust_code`='$cid' order by `tp`.`timestamp` desc";

    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $id = $ROW['id'];
      $ccode = $ROW['cust_code'];
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      $btnShow = '<a href="patient_treatment.php?pgid='.$_POST['pgid'].'&cid='.$ccode.'&tmpid='.$id.'&is_invoice='.$ROW['is_invoice'].'&apid='.$_POST['apid'].'" class="btn btn-icon btn-primary" data-bs-toggle="tooltip" data-bs-placement="top" title="View"><span class="tf-icons bx bx-list-ul"></span></a>';
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-warning editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></a>';
      }
      else{
        $btnEdit = "";
      }
      
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      }
      
      $sub_array = array(); 
      $sub_array[] = '<center>'.$i = ($i + 1).'</center>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $sub_array[] = $ROW['plan_title'] ;
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.($ROW['is_invoice']==0?'Pending':'Invoice').'</center>';
      $UID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` AS `s` WHERE `s`.`id` = '$uid'"));
      if ($UID['staff_position_id'] == 1 || $uid == $ROW['user_id']) { 
        $sub_array[] = '<center>'.$btnShow.' '.$btnEdit.' '.$btnDelete.'</center>';
      } else { $sub_array[] = '<center>'.$btnShow.'</center>';}
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
    );
    echo  json_encode($output);
  }

  function fnInsert() {
    global $CON;
    $code = $_POST['cid'];
    $uid = $_POST['uid'];
    $apid = $_POST['apid']; // appointment id
    $status = 1;
    //
    $CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_customer` WHERE `cust_code` = '$code' LIMIT 1"));
    $cid = $CUST['id'];
    $SQL = "INSERT INTO `tbl_treatment_plan` (`cust_id`, `user_id`, `plan_status`,`apid`) VALUES ('$cid', '$uid', '$status','$apid')";
    $QUERY = mysqli_query($CON, $SQL);
    //
    $ROW = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_treatment_plan` ORDER BY `id` DESC LIMIT 1"));
    $ccode = $ROW['id'];
    // update appointment status     
    $SQL_AP = "UPDATE `tbl_appointment` SET `appo_status` = 3 WHERE `id` = '$apid'";
    mysqli_query($CON, $SQL_AP);

    $data = array('tmpid' => $ccode);
    echo json_encode($data);
  }

  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $title = $_POST['title'];
    $SQL = "UPDATE `tbl_treatment_plan` SET `plan_title` = '$title' WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
?>