<?php
require_once('../Connections/FGSP.php'); 
mysql_select_db($database_FGSP, $FGSP)or die(mysql_error());

if(isset($_POST['btn-upload']))
{    
     
 $file = rand(1000,100000)."-".$_FILES['file']['name'];
    $file_loc = $_FILES['file']['tmp_name'];
 $file_size = $_FILES['file']['size'];
 $file_type = $_FILES['file']['type'];
 $folder="uploads/";
 
 // to open the content and assign to $content variable
 $fp = fopen($file_loc, 'r');
 $content = fread($fp, filesize($file_loc));
 $content = addslashes($content);
 fclose($fp);
 
 // new file size in KB
 $new_size = $file_size/1024;  
 // new file size in KB
 
 // make file name in lower case
 $new_file_name = strtolower($file);
 // make file name in lower case
 
 $final_file=str_replace(' ','-',$new_file_name);
 
 $UID = 100;
 $PgmYear = 2017;
 
 if(move_uploaded_file($file_loc,$folder.$final_file))
 {
  
  $sql="INSERT INTO tblsqdocuments(docName,docType,docSize,docContent,UID,PgmYear) VALUES('$final_file','$file_type','$new_size','$content','$UID','$PgmYear')";
  mysql_query($sql) or die('Error, query failed');
  
  ?>
  <script>
  alert('successfully uploaded');
        window.location.href='ControlPanel2.php?success';
        </script>
  <?php
 }
 else
 {
  ?>
  <script>
  alert('error while uploading file');
        window.location.href='Documents1.php?fail';
        </script>
  <?php
 }
}
?>