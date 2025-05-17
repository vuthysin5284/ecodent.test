<?php
	include_once ('../inc/config.php');
	include_once ('../inc/setting.php');
	
  $qid = $_POST['qid'];
	if ($qid == 1) { fnTable(); }
	else if ($qid == 2) { fnUpdate(); }
	else {}

  function fnTable() {
    global $CON;
    $cid = $_POST['cid'];
		$CUST = mysqli_fetch_assoc(mysqli_query($CON, "SELECT `med_history` FROM `tbl_customer` WHERE `cust_code` = '$cid' LIMIT 1 "));
    $med_history = $CUST['med_history'];
    $med = explode(', ', $med_history);
    $SQL = "SELECT * FROM `tbl_medical_history`";
		$QUERY = mysqli_query($CON, $SQL);
    $i = 0;
    $o .= '<tr>';
    $o .= '<td colspan="4" class="text-center fw-bolder">តើអ្នកធ្លាប់មានអាការៈ និងរោគសញ្ញាដូចខាងក្រោមដែរឬទេ? (Have you ever had any of the following?)</td>';
    $o .= '</tr>';
    while($ROW = mysqli_fetch_assoc($QUERY)) {
      if ($med[$i] == 0) {
        $o .= '<tr>';
        $o .= '<td class="text-center">'.($i + 1).'</td>';
        $o .= '<td class="text-left">'.$ROW['medical_history'].'</td>';
        $o .= '<td class="text-center"><input name="med['.$i.']" class="form-check-input" type="radio" value="1"></td>';
        $o .= '<td class="text-center"><input name="med['.$i.']" class="form-check-input" type="radio" value="0" checked=""></td>';
        $o .= '</tr>';
      } else {
        $o .= '<tr>';
        $o .= '<td class="text-center">'.($i + 1).'</td>';
        $o .= '<td class="text-left table-warning">'.$ROW['medical_history'].'</td>';
        $o .= '<td class="text-center"><input name="med['.$i.']" class="form-check-input" type="radio" value="1" checked=""></td>';
        $o .= '<td class="text-center"><input name="med['.$i.']" class="form-check-input" type="radio" value="0"></td>';
        $o .= '</tr>';
      }
      $i = $i + 1;
    }
		echo $o;
  }

  function fnUpdate() {
    global $CON;
    $cid = $_POST['cid'];
    $med_history = $_POST['med'];
    foreach ($med_history as $med) {
      $history .= $med.', ';
    }
		$history = rtrim($history, ', ');
    $SQL = "UPDATE `tbl_customer` SET `med_history` = '$history' WHERE `cust_code` = '$cid'";
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }
?>