<?php
  ob_start();
  include_once('config.php');
  mysqli_query($CON, "set character_set_client='utf8'"); 
  mysqli_query($CON, "set character_set_results='utf8'"); 
  mysqli_query($CON, "set collation_connection='utf8_general_ci'"); 
  $tables = '*';
    if($tables == '*') {
      $tables = array();
      $result = mysqli_query($CON, "SHOW TABLES");
      while($row = mysqli_fetch_row($result)){
        $tables[] = $row[0];
      }
    } else {
      $tables = is_array($tables) ? $tables : explode(',', $tables);
    }
    foreach($tables as $table) {
      $optimize_table = mysqli_query($CON, "OPTIMIZE TABLE `".$table."`");			   
      $repair_table = mysqli_query($CON, "REPAIR TABLE `".$table."`");
      $result = mysqli_query($CON, "SELECT * FROM `".$table."`");  
      $fields_amount = mysqli_num_fields($result);
      $rows_num =	mysqli_num_rows($result);
      $drop_table = "DROP TABLE IF EXISTS `".$table."`;";
      $res = mysqli_query($CON, "SHOW CREATE TABLE `".$table."`"); 
      $TableMLine = mysqli_fetch_row($res);
      $content = (!isset($content) ?  '' : $content). "\n\n".$drop_table . "\n\n".$TableMLine[1].";\n\n";
      for ($i = 0, $st_counter = 0; $i < $fields_amount;   $i++, $st_counter=0) {
        while($row = mysqli_fetch_row($result)) {
          if ($st_counter%300== 0 || $st_counter == 0 ) {
            $content .= "\nINSERT INTO ".$table." VALUES";
          }
          $content .= "\n(";
          for($j=0; $j<$fields_amount; $j++) { 
            $row[$j] = str_replace("\n","\\n", addslashes($row[$j]) ); 
            if (isset($row[$j])) {
              $content .= '"'.$row[$j].'"' ; 
            } else {   
              $content .= '""';
            }     
            if ($j<($fields_amount-1)){
              $content .= ',';
            }      
          }
          $content .= ")";
          if((($st_counter+1) % 300 == 0 && $st_counter != 0) || $st_counter+1 == $rows_num) {   
             $content .= ";";
          } else {
            $content .= ",";
          }
          $st_counter = $st_counter+1;
        }
        if(substr($content, -1)==','){
          $content=substr_replace($content, ";", -1);
        }
      } 
    }
    // $handle1 = 'database-'.date("Y-m-d-H-i-s").'.sql';
    $handle1 = 'dentalsystem_db_backup.sql';
    $handle = fopen('../images/backUpDB/'.$handle1, 'w');
    fwrite($handle, $content);
    fclose($handle);
    $zip = new ZipArchive;
    if ($zip->open('../images/backUpDB/'.$handle1.'.zip', ZipArchive::CREATE) === TRUE){
      $zip->addFile('../images/backUpDB/'.$handle1);
      $zip->close();
    }
    $path = '../images/backUpDB/';
    if ($handle = opendir($path)) {
      while (false !== ($file = readdir($handle))) { 
        $filelastmodified = filemtime($path . $file);
        if((time() - $filelastmodified) > 30*24*3600){
          unlink($path . $file);
        }
      }
      closedir($handle); 
    }
?>