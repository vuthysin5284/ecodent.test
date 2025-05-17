<?php
  include_once ('../inc/config.php');
  global $CON;

  $SQL = "SELECT * FROM `tbl_invoice_treatment` WHERE `tmt_price` = 0";
  $QUERY = mysqli_query($CON, $SQL);
  while ($ROW = mysqli_fetch_assoc($QUERY)) {
    $id = $ROW['id'];
    $tmsv_id = $ROW['tmsv_id'];
    $DEFAULT_PRICE = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `service_price` FROM `tbl_treatment_service` WHERE `id` = '$tmsv_id'"));
    $service_price = $DEFAULT_PRICE['service_price'];
    mysqli_query($CON, "UPDATE `tbl_invoice_treatment` SET `tmt_price` = '$service_price' WHERE `id` = '$id'");
    $status = ($QUERY == TRUE) ? 'Success' : 'Failed';
    echo $status;
  } 
?>