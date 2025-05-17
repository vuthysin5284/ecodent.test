<?php
  session_start();
  include_once ('../inc/config.php');
  include_once ('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow ('tbl_submenu'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else { }
  
  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "DELETE FROM `tbl_submenu` WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $SQL = "SELECT `tbl_submenu`.*, `tbl_menu`.`menu_name`, `tbl_menu`.`menu_icon`, `tbl_menu`.`menu_order` FROM `tbl_submenu` INNER JOIN `tbl_menu` ON `tbl_submenu`.`menu_id` = `tbl_menu`.`id`";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL));
    $output = array();
    $columns = array(
      0 => 'id',
      1 => 'menu_icon',
      2 => 'menu_name',
      3 => 'sub_menu_name',
      4 => 'sub_menu_kh',
      5 => 'sub_menu_link',
      6 => 'sub_menu_order',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " WHERE `menu_name` LIKE '%". $search_value ."%'";
    }
    if (isset($_POST['order'])) {
      $column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `menu_order` ASC, `sub_menu_order` ASC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT  ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning mb-2 editBtn"><span class="tf-icons bx bx-edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'"  class="btn btn-icon btn-danger mb-2 deleteBtn"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      }
      
      $sub_array = array();
      $sub_array[] = '<center>'.$ROW['id'].'</center>';
      $sub_array[] = '<a href="javascript:void();" class="btn btn-icon btn-secondary"><span class="tf-icons '.$ROW['menu_icon'].'"></span></a>';
      $sub_array[] = $ROW['menu_name'];
      $sub_array[] = $ROW['sub_menu_name'];
      $sub_array[] = $ROW['sub_menu_kh'];
      $sub_array[] = $ROW['sub_menu_link'];
      $sub_array[] = '<center>'.$ROW['sub_menu_order'].'</center>';
      $sub_array[] = '<center>'.$btnEdit.' '.$btnDelete.'</center>';
      $data[] = $sub_array;
    }
    $output = array(
      'draw' => intval($_POST['draw']),
      'recordsTotal' => $count_rows,
      'recordsFiltered' => $total_all_rows,
      'data' => $data,
    );
    echo json_encode($output);
  }

  function getDataRow() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "SELECT * FROM `tbl_submenu` WHERE id = '$id' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }

  function fnInsert() {
    global $CON;
    $menuCategory = $_POST['menuCategory'];
    $menuName = $_POST['menuName'];
    $menuKh = $_POST['menuKh'];
    $menuUrl = $_POST['menuUrl'];
    $menuOrder = $_POST['menuOrder'];
    $SQL = "INSERT INTO `tbl_submenu` (`menu_id`, `sub_menu_name`, `sub_menu_kh`, `sub_menu_link`, `sub_menu_order`) VALUES ('$menuCategory', '$menuName', '$menuKh', '$menuUrl', '$menuOrder')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $menuId = $_POST['menuCategory'];
    $menuName = $_POST['menuName'];
    $menuKh = $_POST['menuKh'];
    $menuLink = $_POST['menuUrl'];
    $menuOrder = $_POST['menuOrder'];
    $SQL = "UPDATE `tbl_submenu` SET `menu_id` = '$menuId', `sub_menu_name` = '$menuName', `sub_menu_link` = '$menuLink', `sub_menu_kh` = '$menuKh', `sub_menu_order` = '$menuOrder' WHERE id = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'true') : array('status' => 'false');
    echo json_encode($data);
  }

  function getLastId() {
    global $CON;
    $SQL = "SELECT `id` FROM `tbl_submenu` ORDER BY `id` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }
?>