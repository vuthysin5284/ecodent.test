<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow('tbl_clinical_note'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "DELETE FROM `tbl_clinical_note` WHERE id = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == true) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $uid = $_POST['uid'];
    $cid = $_POST['cid'];
    $USER = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `staff_position_id` FROM `tbl_staff` WHERE `id` = '$uid'"));
    $SQL = "SELECT `n`.`id`, DATE_ADD(`n`.`timestamp`, INTERVAL 10 HOUR) as `timestamp`, `clinical_note`, `tooth_id`, `staff_fname`, `n`.`user_id` FROM `tbl_clinical_note` AS `n` INNER JOIN `tbl_customer` AS `c` ON (`n`.`cust_id` = `c`.id) INNER JOIN `tbl_staff` AS `s` ON (`n`.`user_id` = `s`.`id`) WHERE `cust_code` = '$cid'";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(  
        1 => 'timestamp',
        2 => 'tooth_id',
        3 => 'clinical_note',
        4 => 'user_id'
      );
      if (isset($_POST['search']['value'])) {
        $search_value = $_POST['search']['value'];
        $SQL .= " AND `clinical_note` LIKE '%". $search_value ."%'";
      }
      if (isset($_POST['order'])) {
        $column_name = $_POST['order'][0]['column'];
        $order = $_POST['order'][0]['dir'];
        $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
      }
      else { $SQL .= " ORDER BY `n`.`timestamp` DESC"; }
      if ($_POST['length'] != -1) {
        $start = $_POST['start'];
        $length = $_POST['length'];
        $SQL .= " LIMIT  ".$start.", ".$length;
      } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $user_id = $ROW['user_id'];
      $timestamp = date('d - M - Y', strtotime($ROW['timestamp'])).'<br>'.date('H : i A', strtotime($ROW['timestamp']));
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></span></a>';
      }
      else{
        $btnDelete = "";
      }

      $TQ = mysqli_query($CON, "SELECT `id`, `tooth_description` FROM `tbl_tooth_item`");
      $tooth_id = $ROW['tooth_id'];
      $tid = explode(',', $tooth_id);
      $tooth = '';
      while ($TOOTH = mysqli_fetch_assoc($TQ)) {
        foreach($tid as $t) {
          $tooth .= ($TOOTH['id'] == $t) ? $TOOTH['tooth_description'].', ' : '';
        }
      }
      $tooth = rtrim($tooth, ', ');
      $sub_array = array(); 
      $sub_array[] = '<center>'.($i += 1).'</center>';
      $sub_array[] = '<center>'.$timestamp.'</center>';
      $sub_array[] = '<center>'.$tooth.'</center>';
      $sub_array[] = $ROW['clinical_note'];
      $sub_array[] = '<center>'.$ROW['staff_fname'].'</center>';
      $sub_array[] = '<center>'.$btnEdit.' '.$btnDelete.'</center>';
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
    $ccode = $_POST['cid'];
    $CID = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `id` FROM `tbl_customer` WHERE `cust_code` = '$ccode'"));
    $cid = $CID['id'];
    $uid = $_POST['dentist'];
    $note = $_POST['note'];
    $datetime = $_POST['datetime'];
    $tid = $_POST['tooth'];
    foreach ($tid as $t) {
      $tooth .= $t.',';
    }
    $tooth = rtrim($tooth, ', ');
    $SQL = "INSERT INTO `tbl_clinical_note` (`timestamp`, `cust_id`, `tooth_id`, `clinical_note`, `user_id`) VALUES ('$datetime', '$cid', '$tooth', '$note', '$uid')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $note = $_POST['note'];
    $tid = $_POST['tooth'];
    $dentist = $_POST['dentist'];
    $datetime = $_POST['datetime'];
    foreach ($tid as $t) {
      $tooth .= $t.',';
    }
    $tooth = rtrim($tooth, ', ');
    $SQL = "UPDATE `tbl_clinical_note` SET `timestamp` = '$datetime', `tooth_id` = '$tooth', `clinical_note` = '$note', `user_id` = '$dentist' WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }
?>