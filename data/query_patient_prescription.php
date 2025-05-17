<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];

  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { searchData(); }
  else if ($qid == 3) { fnInsert(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "DELETE FROM `tbl_prescription` WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function searchData() {
    global $CON;
    $output = '';
    $str = $_POST['str'];
    $SQL = "SELECT `id`, `pres_medicine` FROM `tbl_prescription` WHERE `pres_medicine` LIKE '".$str."%' GROUP BY `pres_medicine` ORDER BY `pres_medicine` ASC";
    $QUERY = mysqli_query($CON, $SQL);
    if (mysqli_num_rows($QUERY) > 0) {
      while ($ROW = mysqli_fetch_assoc($QUERY)) {
        $output .= '<a href="javascript:void(0);" class="list-group-item list-group-item-action med-data">'.$ROW['pres_medicine'].'</a>';
      }
    }
    echo $output;
  }
  
  function fnTable() {
    global $CON;
    $pid = $_POST['pgid'];
    $SQL = "SELECT `p`.* FROM `tbl_prescription` AS `p` INNER JOIN `tbl_diagnosis` AS `d` ON (`p`.`pres_id` = `d`.`id`) WHERE `d`.`id` = '$pid'";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $qty = 1;
      $status = ($ROW['pres_status'] == 0) ? 'After Meal' : 'Before Meal';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn mb-2" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      $sub_array = array(); 
      $sub_array[] = '<center>'.($i += 1).'</center>';
      $sub_array[] = $ROW['pres_medicine'];
      $sub_array[] = '<center>'.$status.'</center>';
      $sub_array[] = '<center>'.$ROW['pres_m'].'</center>';
      $sub_array[] = '<center>'.$ROW['pres_a'].'</center>';
      $sub_array[] = '<center>'.$ROW['pres_e'].'</center>';
      $sub_array[] = '<center>'.$ROW['pres_duration'].' days</center>';
      $sub_array[] = '<center>'.$btnDelete.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows ,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
    );
    echo json_encode($output);
  }

  function fnInsert() {
    global $CON;
    $pid = $_POST['pgid'];
    $medicine = $_POST['medicine'];
    $category = $_POST['category'];
    $morning = $_POST['morning'];
    $afternoon = $_POST['afternoon'];
    $evening = $_POST['evening'];
    $duration = $_POST['duration'];
    $instruction = $_POST['instruction'];
    $SQL = "INSERT INTO `tbl_prescription` (`pres_id`, `pres_medicine`, `pres_cate_id`, `pres_m`, `pres_a`, `pres_e`, `pres_duration`, `pres_status`) VALUES ('$pid', '$medicine', '$category', '$morning', '$afternoon', '$evening', '$duration', '$instruction')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
  
  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $cid = $_POST['cid'];
    $cid = trim($cid, 'P-');
    $custId = (int) $cid;
    $uid = $_POST['uid'];
    $diagnosis = $_POST['diagnosis'];
    $SQL = "UPDATE `tbl_prescription` SET `cust_id` = '$custId', `pres_diagnosis` = '$diagnosis', `user_id` = '$uid' WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

?>