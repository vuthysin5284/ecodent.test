<?php
  include_once ('../inc/config.php');
  include_once ("../inc/class.upload.php");
  include_once ("../inc/setting.php");
  $qid = $_POST['qid'];
  if ($qid == 1) { getData(); }
  else if ($qid == 2) { fnTable(); }
  else { }

  function fnTable() {
    global $CON;
    $pid = $_POST['pgid'];
    $SQL = "SELECT `p`.*, `measure_kh` FROM `tbl_prescription` AS `p` INNER JOIN `tbl_measurement` AS `m` ON (`p`.`pres_cate_id` = `m`.`id`) WHERE `pres_id` = '$pid'";
    $QUERY = mysqli_query($CON, $SQL);
    $o = '';
    $i = 0;
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $m = $ROW['pres_m'];
      $a = $ROW['pres_a'];
      $e = $ROW['pres_e'];
      $duration = $ROW['pres_duration'];
      $measure = $ROW['measure_kh'];
      $categ = $ROW['pres_cate_id'];
      $measure = ($categ == 2) ? 'ដង' : $ROW['measure_kh'];
      $morning = ($m == 0) ? '' : 'ព្រឹក '.$m.' '.$measure;
      $afternoon = ($a == 0) ? '' : 'រសៀល '.$a.' '.$measure;
      $evening = ($e == 0) ? '' : 'ល្ងាច '.$e.' '.$measure;
      $pres_status = ($ROW['pres_status'] == 0) ? '(ក្រោយពេលបាយ)' : '(មុនពេលបាយ)';
      if ($categ == 1) {
          $qty = 1;
          $status = 'ខ្ពល់មាត់ '.($m + $a + $e).' ដង/ថ្ងៃ ម្ដងរយៈពេល 1 នាទី';
          $pres_status = '';
      } else if ($categ == 2) {
          $qty = 1;
          $status = 'លាប '.$morning.' '.$afternoon.' '.$evening;
          $pres_status = '';
      } else {
          $qty = ($m + $a + $e) * $duration;
          $status = 'ពិសា '.$morning.' '.$afternoon.' '.$evening;
      }
      $o .= '<tr>';
      $o .= '<td class="text-center text-dark">'.($i += 1).'</td>';
      $o .= '<td class="text-left text-dark">'.$ROW['pres_medicine'].'</td>';
      $o .= '<td class="text-center text-dark">'.$qty.' '.$ROW['measure_kh'].'</td>';
      $o .= '<td class="text-left text-dark">'.$status.'<br><small class="text-muted">'.$pres_status.'</small></td>';
      $o .= '<td class="text-center text-dark">'.$ROW['pres_duration'].' ថ្ងៃ</td>';
      $o .= '</tr>';
    }
    echo $o;
  }

  function getData() {
    global $CON;
    $pid = $_POST['pgid'];
    $SQL = "SELECT `c`.*, `pres_code`, `pres_diagnosis` FROM `tbl_customer` AS `c` INNER JOIN `tbl_diagnosis` AS `d` ON (`d`.`cust_id` = `c`.`id`) WHERE `d`.`id` = '$pid' LIMIT 1";
    $QUERY = mysqli_query($CON, $SQL);
    $o = '';
    while ($ROW = mysqli_fetch_assoc($QUERY)) {
      $id = $ROW['id'];
      $code = $ROW['pres_code'];
      $name = $ROW['cust_fname'];
      $contact = $ROW['cust_contact'];
      $address = $ROW['cust_address'];
      $diagnosis = $ROW['pres_diagnosis'];
      $presid = 'PRES-'. sprintf('%05d', $pid);
      $gender = ($ROW['cust_gender'] == 0) ? 'ស្រី' : 'ប្រុស';
      $age = (date('Y') - date('Y', strtotime($ROW['cust_dob'])));
      $o .= '<div class="row align-items-top mb-3">';
      $o .= '<div class="col-5 text-body text-dark">កាលបរិច្ឆេទ / Date &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.date('d-M-Y').'</strong></div>';
      $o .= '<div class="col text-body text-dark">លេខកូដ / PID &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.$presid.'</strong></div>';
      $o .= '</div>';
      $o .= '<div class="row align-items-top mb-3">';
      $o .= '<div class="col-5 text-body text-dark">អតិថិជន / Patient &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.$name.'</strong></div>';
      $o .= '<div class="col text-body text-dark">អាយុ / Age &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.$age.' ឆ្នាំ</strong> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; ភេទ / Sex &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; : &nbsp; <strong class="text-dark">'.$gender.'</strong></div>';
      $o .= '</div>';
      $o .= '<div class="row align-items-top mb-3">';
      $o .= '<div class="col-5 text-body text-dark">លេខទូរស័ព្ទ / Contact &nbsp; : &nbsp; <strong class="text-dark">'.$contact.'</strong></div>';
      $o .= '<div class="col text-body text-dark">អាស័យដ្ឋាន / Address &nbsp; : &nbsp; <strong class="text-dark">'.$address.'</strong></div>';
      $o .= '</div>';
      $o .= '<div class="row align-items-top">';
      $o .= '<div class="text-body text-dark">រោគវិនិច្ឆ័យ / Diagnosis : &nbsp; <strong class="text-dark">'.$diagnosis.'</strong></div>';
      $o .= '</div>';
      $o .= '';
    }
    echo $o;
  }

?>