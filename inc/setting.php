<?php
	function shortNumber($num){
			$units = ['', 'K', 'M', 'B', 'T'];
      $total = $num;
			for ($i = 0; $num >= 1000; $i++) {
					$num /= 1000;
			}
			if ($total < 1000) {
				return round($num, 0, PHP_ROUND_HALF_DOWN) . $units[$i];
			} else {
        return round($num, 2, PHP_ROUND_HALF_DOWN) . $units[$i];
      }
	}

	function reNumPermission ($value) {
  	$noSpace = str_replace(' ', '', $value);
  	$data = explode(',', $noSpace);
  	$str = '';
  	foreach ($data as $val) {
  		$str .= $val.', ';
  	}
  	return $str;
  }

	function decodeId ($data, $str) {
		$id = $_POST[$data];
		$cid = trim($id, $str);
		return (int) $cid;
	}

	function JSResult () {
		if($query == true) {
			$data = array('status' => 'true'); 
			echo json_encode($data);  
		} else {
			$data = array('status' => 'false');
			echo json_encode($data);
		}
	 }

	function getJSRow ($tbl_name, $col_name = 'id', $val_name = 'id') {
		global $CON;
	 	$value = $_POST[$val_name];
		$sql = "SELECT * FROM `".$tbl_name."` WHERE `".$col_name."` = '".$value."' LIMIT 1 ";
		$query = mysqli_query($CON, $sql);
		$row = mysqli_fetch_assoc($query);
		echo json_encode($row);
	}

	function getJSLastId($table_name) {
		global $CON;
		$sql = "SELECT `id` FROM `'$tbl_name'` WHERE ORDER BY `id` DESC";
		$query = mysqli_query($CON, $sql);
		$row = mysqli_fetch_assoc($query);
		echo json_encode($row);
	}

	function inpImage($name, $image_src)  {
		$o = '';
		$o .= '<div class="d-flex align-items-start align-items-sm-center gap-4 mb-4">';
		$o .= 	'<img src="'.$image_src.'" alt="img" class="d-block rounded" height="100" width="100" id="uploadedAvatar" />';
		$o .= 	'<div class="button-wrapper">';
		$o .= 		'<label for="upload" class="btn btn-primary me-2 mb-4" tabindex="0">';
		$o .= 			'<span class="d-none d-sm-block">Upload Photo</span>';
		$o .= 			'<i class="bx bx-upload d-block d-sm-none"></i>';
		$o .= 			'<input type="file" name="'.$name.'" id="upload" class="account-file-input" hidden accept="image/png, image/jpeg" />';
		$o .= 		'</label>';
		$o .= 		'<p class="text-muted mb-0">Allowed JPG, or PNG. Max Size of 25MB.</p>';
		$o .= 	'</div>';
        $o .= '</div>';
        return $o;
	}

	function inpText($label, $name, $placeholder, $prop = 1) {
		if ($prop == 1 ) { $property = 'required'; } 
		else if ($prop == 2 ) { $property = 'required readonly'; } 
		else { $property = ''; }
		$o = '';
		$o .= '<div class="col">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
		$o .= '<input type="text" id="'.$name.'" name="'.$name.'" class="form-control" placeholder="'.$placeholder.'" '.$property.' />';
		$o .= '</div>';
		return $o;
	}

	function inpTextarea($label, $name, $placeholder) {
		$o = '';
		$o .= '<div class="col">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
		$o .= '<textarea class="form-control" id="'.$name.'" name="'.$name.'"  placeholder="'.$placeholder.'"></textarea>';
		$o .= '</div>';
		return $o;
	}

	function inpNum($label, $name, $placeholder, $prop = 1) {
		if ($prop == 1 ) { $property = 'required'; } else { $property = 'required readonly'; }
		$o = '';
		$o .= '<div class="col">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
		$o .= '<input type="number" id="'.$name.'" name="'.$name.'" class="form-control" placeholder="'.$placeholder.'" '.$property.' min="0" max="100" />';
		$o .= '</div>';
		return $o;
	}

	function inpPwd($label, $name, $prop = 1) {
		if ($prop == 1 ) { $property = 'required'; } else if ($prop == 2 ) { $property = 'required readonly'; } else { $property = ''; }
		$o = '';
		$o .= '<div class="col form-password-toggle">';
		$o .= '<div class="d-flex justify-content-between">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
		$o .= '</div>';
		$o .= '<div class="input-group input-group-merge">';
		$o .= '<input type="password" id="'.$name.'" name="'.$name.'" class="form-control" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" aria-describedby="password" '.$property.' />';
		$o .= '<span class="input-group-text cursor-pointer"><i class="bx bx-hide"></i></span>';
		$o .= '</div>';
		$o .= '</div>';
		return $o;
	}

	function inpHidden($name, $prop = 1) {
		if ($prop == 1 ) { $property = 'required'; } else { $property = 'required readonly'; }
		$o = '';
		$o .= '<input type="hidden" id="'.$name.'" name="'.$name.'" class="form-control" '.$property.' />';
		return $o;
	}

	function inpDateTime($label, $name, $placeholder='', $prop = 1) {
		if ($prop == 1 ) { $property = 'required'; } else { $property = 'required readonly'; }
		$o = '';
		$o .= '<div class="col">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
		$o .= '<input type="datetime-local" id="'.$name.'" name="'.$name.'" class="form-control" placeholder="'.$placeholder.'" '.$property.' />';
		$o .= '</div>';
		return $o;
	}

	function inpDate($label, $name, $placeholder='', $prop = 1) {
		if ($prop == 1 ) { $property = 'required'; } else { $property = 'required readonly'; }
		$o = '';
		$o .= '<div class="col">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
		$o .= '<input type="date" id="'.$name.'" name="'.$name.'" class="form-control" placeholder="'.$placeholder.'" '.$property.' />';
		$o .= '</div>';
		return $o;
	}

	function selectGender($label, $name) {
		$o = '';
		$o .= '<div class="col">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
        $o .= '<select class="form-select" id="'.$name.'" name="'.$name.'" required >';
        $o .= '<option value="1">Male</option>';
        $o .= '<option value="0">Female</option>';
        $o .= '</select>';
        $o .= '</div>';
        return $o;
	}

	function selectData($label, $name, $sql, $data) {
		global $CON;
		$o = '';
		$o .= '<div class="col">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
		$o .= '<select class="form-select" id="'.$name.'" name="'.$name.'" required >';
		$o .= '<option hidden value="">--- select item ---</option>';
		$query = mysqli_query($CON, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
	        $i = $row['id'];
	        $d = $row[$data];
	        $o .= '<option value="'.$i.'">'.$d.'</option>';
        }
        $o .= '</select>';
        $o .= '</div>';
        return $o;
	}

  function select2Data($label, $name, $sql, $data) {
		global $CON;
		$o = '';
		$o .= '<div class="col">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
		$o .= '<select class="form-select" id="'.$name.'" name="'.$name.'" required data-placeholder="--- select item ---">';
		$query = mysqli_query($CON, $sql);
		while ($row = mysqli_fetch_assoc($query)) {
			$i = $row['id'];
			$d = $row[$data];
			$o .= '<option value="'.$i.'">'.$d.'</option>';
		}
		$o .= '</select>';
		$o .= '</div>';
		return $o;
	}
	
	function select2Datas($label, $name, $sql, $data) {
		global $CON;
		$o = '';
		$o .= '<div class="col">';
		$o .= '<label for="'.$name.'" class="form-label">'.$label.'</label>';
		$o .= '<select class="form-select" id="'.$name.'" name="'.$name.'[]" required multiple data-placeholder="--- select item(s) ---">';
		$query = mysqli_query($CON, $sql);
        while ($row = mysqli_fetch_assoc($query)) {
	        $i = $row['id'];
	        $d = $row[$data];
	        $o .= '<option value="'.$i.'">'.$d.'</option>';
        }
        $o .= '</select>';
        $o .= '</div>';
        return $o;
	}
?>