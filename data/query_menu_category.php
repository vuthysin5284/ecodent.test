<?php
  include_once ('../inc/config.php');
  include_once ('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow ('tbl_menu'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "DELETE FROM `tbl_menu` WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $SQL = "SELECT * FROM `tbl_menu`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      0 => 'id',
      1 => 'menu_name',
      2 => 'menu_kh',
      3 => 'menu_icon',
      4 => 'menu_order',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " WHERE `menu_name` LIKE '%". $search_value ."%'";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `menu_order` asc"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $sub_array = array();
      $sub_array[] = '<center>'.$ROW['id'].'</center>';
      $sub_array[] = $ROW['menu_name'];
      $sub_array[] = $ROW['menu_kh'];
      $sub_array[] = '<center><a href="javascript:void();" class="btn btn-icon btn-secondary"><span class="tf-icons '.$ROW['menu_icon'].'"></span></a></center>';
      $sub_array[] = '<center>'.$ROW['menu_order'].'</center>';
      $sub_array[] = '<center><a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning editBtn"><span class="tf-icons bx bx-edit"></span></a>  <a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger deleteBtn"><span class="tf-icons bx bx-trash"></a></center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
    );
    echo  json_encode($output);
  }

  function getDataRow() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "SELECT * FROM `tbl_menu` WHERE `id` = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }
  
  function fnInsert() {
    global $CON;
    $name = $_POST['name'];
    $namekh = $_POST['namekh'];
    $icon = $_POST['icon'];
    $order = $_POST['order'];
    $SQL = "INSERT INTO `tbl_menu` (`menu_name`, `menu_kh`, `menu_icon`, `menu_order`) VALUES ('$name', '$namekh', '$icon', '$order')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }
  
  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $name = $_POST['name'];
    $namekh = $_POST['namekh'];
    $icon = $_POST['icon'];
    $order = $_POST['order'];
    $SQL = "UPDATE `tbl_menu` SET `menu_name` = '$name', `menu_kh` = '$namekh', `menu_icon` = '$icon', `menu_order` = '$order' WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

  function getLastId() {
    global $CON;
    $SQL = "SELECT `id` FROM `tbl_menu` ORDER BY `id` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

?>