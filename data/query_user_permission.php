<?php
	include_once ('../inc/config.php');
	include_once ('../inc/setting.php');
	
  	$qid = $_POST['qid'];
	if ($qid == 1) { fnTable(); }
	else if ($qid == 2) { updateRows(); }
	else if ($qid == 3) { getJSRow('tbl_staff'); }
	else if ($qid == 4) { fnUpdate(); }
	else {}

  function fnTable() {
		global $CON;
		$lang = $_POST['lang'];
		$sid = $_POST['sid'];
		$SQLs = "SELECT `id`, `user_permission`,user_add_perm,user_edit_perm,user_delete_perm FROM `tbl_staff` WHERE `id` = '$sid' LIMIT 1 ";
		$QUERYs = mysqli_query($CON, $SQLs);
		$ROWs = mysqli_fetch_assoc($QUERYs);
		$permission = $ROWs['user_permission'];
		$add = $ROWs['user_add_perm'];
		$edit = $ROWs['user_edit_perm'];
		$delete = $ROWs['user_delete_perm'];
		//
		$permissions = explode(', ', $permission);
		$add = explode(', ', $add);
		$edit = explode(', ', $edit);
		$delete = explode(', ', $delete);
		//
		$SQL = "SELECT `sm`.`id`, `menu_name`, `menu_kh`, `menu_order`, `sub_menu_name`, `sub_menu_kh`, `sub_menu_order` FROM `tbl_submenu` AS `sm` INNER JOIN `tbl_menu` AS `m` ON `sm`.`menu_id` = `m`.`id` ORDER BY `menu_order`, `sub_menu_order` ASC";
		$QUERY = mysqli_query($CON, $SQL);
		if (mysqli_num_rows($QUERY) > 0) {
			$output = '';
			$i = 0;
			while ($ROW = mysqli_fetch_assoc($QUERY)) {
				$id = $ROW['id'];
				foreach ($permissions as $p) {
					if($p == $id) { $st = 'true'; break; } else { $st = 'false'; }
				}
				foreach ($add as $a) {
					if($a == $id) { $sta = 'true'; break; } else { $sta = 'false'; }
				}
				foreach ($edit as $e) {
					if($e == $id) { $ste = 'true'; break; } else { $ste = 'false'; }
				}
				foreach ($delete as $d) {
					if($d == $id) { $std = 'true'; break; } else { $std = 'false'; }
				}

				if ($lang == 1) {
					$menu = $ROW['menu_name'];
					$sub_menu = $ROW['sub_menu_name'];
					$description = 'User can navigate, and modify information in this menu.';
				} else {
					$menu = $ROW['menu_kh'];
					$sub_menu = $ROW['sub_menu_kh'];
					$description = 'អ្នកប្រើប្រាស់អាចចូលមើល និងកែប្រែពត៌មាននៅក្នុងទំព័រនេះបាន';
				}
				$str = ($permissions[$i] == $id) ? 'checked' : '';
				$stra = ($add[$i] == $id) ? 'checked' : '';
				$stre = ($edit[$i] == $id) ? 'checked' : '';
				$strd = ($delete[$i] == $id) ? 'checked' : '';
				$output .= $str;
				$output .= $stra;
				$output .= $stre;
				$output .= $strd;
				$output .= '<tr>';
				$output .= '<td class="text-center">'.($i += 1).'</td>'; 
				$output .= '<td class="text-wrap"><strong>'.$menu.' / '.$sub_menu.'</strong> : <i>'.$description.'<i></td>';
				$output .= '<td><div class="form-check d-flex justify-content-center">';
							if ($st == 'true') {
								$output .= '<input class="form-check-input" type="checkbox" name="permission[]" id="p'.$i.'" value="'.$ROW["id"].'" checked />';
							} else {
								$output .= '<input class="form-check-input" type="checkbox" name="permission[]" id="p'.$i.'" value="'.$ROW["id"].'" />';
							}						
				$output .= '</div>';
				$output .= '</td>'; 
		
				// ADD
				$output .= '<td><div class="form-check d-flex justify-content-center">';  
							if ($sta == 'true') {
								$output .= '<input class="form-check-input" type="checkbox" name="add[]" id="a'.$i.'" value="'.$ROW["id"].'" checked/>';  
							} else {
								$output .= '<input class="form-check-input" type="checkbox" name="add[]" id="a'.$i.'" value="'.$ROW["id"].'" />'; 
							}
				$output .= '</div>';
				$output .= '</td>';
				
				// EDIT
				$output .= '<td><div class="form-check d-flex justify-content-center">';  
							if ($ste == 'true') {
								$output .= '<input class="form-check-input" type="checkbox" name="edit[]" id="e'.$i.'" value="'.$ROW["id"].'" checked/>';  
							} else {
								$output .= '<input class="form-check-input" type="checkbox" name="edit[]" id="e'.$i.'" value="'.$ROW["id"].'" />'; 
							}
				$output .= '</div>';
				$output .= '</td>';
				
				// DELETE
				$output .= '<td><div class="form-check d-flex justify-content-center">';  
							if ($std == 'true') {
								$output .= '<input class="form-check-input" type="checkbox" name="delete[]" id="d'.$i.'" value="'.$ROW["id"].'" checked/>';  
							} else {
								$output .= '<input class="form-check-input" type="checkbox" name="delete[]" id="d'.$i.'" value="'.$ROW["id"].'" />'; 
							} 
				$output .= '</div>';
				$output .= '</td>';


				$output .= '</tr>';
			}
		} else { $output .= 'Loading to table unsuccessfully, SQL : '.$SQL; }
		echo $output;
	}

	function updateRows() {
		global $CON;
		$sid = $_POST['sid'];
		$permission = $_POST['permission']; 
		foreach ($permission as $p) {
			$permissions .= $p.", ";
		}
			$permissions = rtrim($permissions, ', '); 
		// add
		$add = $_POST['add'];
		foreach ($add as $a) {
			$adda .= $a.", ";
		}
		$adda = rtrim($adda, ', '); 
		
		// edit
		$edit = $_POST['edit'];
		foreach ($edit as $e) {
			$edite .= $e.", ";
		}
		$edite = rtrim($edite, ', '); 
		
		// delete
		$delete = $_POST['delete'];
		foreach ($delete as $d) {
			$deleted .= $d.", ";
		}
		$deleted = rtrim($deleted, ', '); 

		$SQL = "UPDATE `tbl_staff` SET `user_permission` = '$permissions',user_add_perm= '$adda',user_edit_perm= '$edite',user_delete_perm= '$deleted' WHERE `id` = '$sid'";
		$QUERY = mysqli_query($CON, $SQL);
		$data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
		echo json_encode($data);
	}
	
  function fnUpdate() {
		global $CON;
    $id = $_POST['id'];
    $username = $_POST['username'];
    $pass = $_POST['password'];
    if ($pass == '') {
    	$SQL = "UPDATE `tbl_staff` SET `username` = '$username' WHERE id = '$id'"; 
    } else {
    	$password = password_hash($pass, PASSWORD_DEFAULT);
    	$SQL = "UPDATE `tbl_staff` SET `username` = '$username', `password` = '$password' WHERE id = '$id'"; 
  	} 
    $QUERY = mysqli_query($CON, $SQL);
    $data = ($QUERY == TRUE) ? array('status' => 'True') : array('status' => 'False');
    echo json_encode($data);
  }
  
?>