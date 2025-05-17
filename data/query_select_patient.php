<?php
  include_once ('../inc/config.php');

  if (!empty($_GET['type']) && $_GET['type'] == 'cust_search') {
    $str = !empty($_GET['search']) ? $_GET['search'] : '';
    $SQL = "SELECT * FROM `tbl_customer` WHERE `cust_fname` LIKE '%".$str."%' AND `cust_status` = 1 limit 15";
    $QUERY = mysqli_query($CON, $SQL);
    $cust_data = array();
    if (mysqli_num_rows($QUERY) > 0) {
      while ($ROW = mysqli_fetch_assoc($QUERY)) {
        $data['id'] = $ROW['id'];
        $data['text'] = $ROW['cust_fname'];
        array_push($cust_data, $data);
      }
    }
    echo json_encode($cust_data);
  }
?>