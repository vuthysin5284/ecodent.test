<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { fnRecovery(); }
  else { }
  
  /* qid : 0 */
  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "DELETE FROM `tbl_staff` WHERE `id` = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnRecovery() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "UPDATE `tbl_staff` SET `staff_status` = 1 WHERE `id` = '$id'";
    $delQuery = mysqli_query($CON, $SQL);
    $data = ($delQuery == TRUE) ? array('status' => 'success') : array('status' => 'failed');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $SQL = "SELECT * FROM `tbl_staff` WHERE `staff_status` = 0";
    $output = array();
    $columns = array(
      0 => 'id',
      2 => 'staff_fname',
      3 => 'staff_gender',
      4 => 'staff_dob',
      5 => 'staff_contact',
      6 => 'staff_address',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND (`staff_fname` LIKE '%". $search_value ."%'";
      $SQL .= " OR `id` LIKE '%".$search_value."%')";
    }
    $SQL .= " GROUP BY `id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `id` DESC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $sid = 'S-'. sprintf('%05d', $ROW['id']);
      $folder = ($ROW['staff_image'] == '0') ? '' : $sid.'/';
      $gender = ($ROW['staff_gender'] == 0) ? 'F' : 'M';
      $age = (date('Y') - date('Y', strtotime($ROW['staff_dob'])));
      $btnShow = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-primary mb-2 recoveryBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Restore"><span class="tf-icons bx bx-history"></span></a>';
      $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger mb-2 deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      $sub_array = array();
      $sub_array[] = '<center>'.$sid.'</center>';
      $sub_array[] = '<center><img src="../images/profiles/'.$folder.''.$ROW['staff_image'].'.jpg" alt="user-avatar" class="d-block rounded" height="50" width="50"></center>';
      $sub_array[] = $ROW['staff_fname'];
      $sub_array[] = '<center>'.$gender.'</center>';
      $sub_array[] = '<center>'.$age.'</center>';
      $sub_array[] = '<center>'.$ROW['staff_contact'].'</center>';
      $sub_array[] = '<center>'.$ROW['staff_address'].'</center>';
      $sub_array[] = '<center>'.$btnShow.' '.$btnDelete.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw'=> intval($_POST['draw']),
      'recordsTotal' =>$count_rows ,
      'recordsFiltered'=>   $total_all_rows,
      'data'=>$data,
    );
    echo  json_encode($output);
  }

?>