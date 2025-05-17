<?php
  session_start();
	include_once ('../inc/config.php');
	include_once ('../inc/setting.php');

  $qid = $_POST['qid'];
  if ($qid == 0) { fnDelete(); }
  else if ($qid == 1) { fnTable(); }
  else if ($qid == 2) { getJSRow ('tbl_treatment_service'); }
  else if ($qid == 3) { fnInsert(); }
  else if ($qid == 4) { fnUpdate(); }
  else if ($qid == 5) { getLastId(); }
  else { }

  function fnDelete() {
    global $CON;
    $id = $_POST['id'];
    $SQL = "DELETE FROM `tbl_treatment_service` WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

  function fnTable() {
    global $CON;
    $status = $_POST['status'];  
    $num_code = $_POST['num_code'];   
    $SQL = "SELECT `s`.*, `prod_category` 
      FROM `tbl_treatment_service` AS `s` 
      INNER JOIN `tbl_product_category` AS `c` ON (`s`.`service_cate_id` = `c`.`id`)
      WHERE ('$status' = 'ALL' or `s`.`status` = '$status')
      AND ('$num_code' = 'ALL' or `c`.`prod_category` = '$num_code')";
    $total_all_rows = mysqli_num_rows(mysqli_query($CON, $SQL)); 
    $output = array();
    $columns = array(
      1 => 'prod_category',
      2 => 'service_description',
      3 => 'service_price',
      4 => 'service_cost',
    );
    if (isset($_POST['search']['value'])) {
      $search_value = $_POST['search']['value'];
      $SQL .= " AND `service_description` LIKE '%". $search_value ."%'";
    }
    if (isset($_POST['order'])) {
    	$column_name = $_POST['order'][0]['column'];
      $order = $_POST['order'][0]['dir'];
      $SQL .= " ORDER BY ".$columns[$column_name]." ".$order."";
    } else { $SQL .= " ORDER BY `prod_category`, `service_description` ASC"; }
    if ($_POST['length'] != -1) {
      $start = $_POST['start'];
      $length = $_POST['length'];
      $SQL .= " LIMIT ".$start.", ".$length;
    } 
    $QUERY = mysqli_query($CON, $SQL);
    $count_rows = mysqli_num_rows($QUERY);
    $data = array();
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $price = $ROW['service_price'];
      $sub_array = array();
      // edit
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_edit_perm']))){ 
        $btnEdit = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-warning editBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Edit"><span class="tf-icons bx bx-edit"></span></a>';
      }
      else{
        $btnEdit = "";
      }
      // delete
      if(in_array($_POST['pgid'], explode(',',$_SESSION['user_delete_perm']))){ 
        $btnDelete = '<a href="javascript:void();" data-id="'.$ROW['id'].'" class="btn btn-icon btn-danger deleteBtn" data-bs-toggle="tooltip" data-bs-placement="top" title="Delete"><span class="tf-icons bx bx-trash"></a>';
      }
      else{
        $btnDelete = "";
      }
      
      $sub_array[] = '<center>'.($i += 1).'</center>';
      $sub_array[] = '<center>'.$ROW['prod_category'].'</center>';
      $sub_array[] = $ROW['service_description'];
      $sub_array[] = '<div class="d-flex justify-content-between p-0"><span>$</span><span>'.number_format($price,2).'</span></div>';'<center>'.$ROW['service_price'].'</center>';
      $sub_array[] = '<center>'.$ROW['service_cost'].' %</center>';
      $sub_array[] = '<center>'.$btnShow.' '.$btnEdit.' '.$btnDelete.'<center>';
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
    $cost = $_POST['cost'];
    $price = $_POST['price'];
    $service = $_POST['service'];
    $category = $_POST['category'];
    $SQL = "INSERT INTO `tbl_treatment_service` (`service_cate_id`, `service_description`, `service_price`, `service_cost`) VALUES ('$category', '$service', '$price', '$cost')";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

  function fnUpdate() {
    global $CON;
    $id = $_POST['id'];
    $cost = $_POST['cost'];
    $price = $_POST['price'];
    $service = $_POST['service'];
    $category = $_POST['category'];
    $SQL = "UPDATE `tbl_treatment_service` SET `service_cate_id` = '$category', `service_description` = '$service', `service_price` = '$price',  `service_cost` = '$cost' WHERE `id` = '$id'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }

  function getLastId() {
    global $CON;
    $SQL = "SELECT `id` FROM `tbl_treatment_service` ORDER BY `id` DESC";
    $QUERY = mysqli_query($CON, $SQL);
    $ROW = mysqli_fetch_assoc($QUERY);
    echo json_encode($ROW);
  }
?>