<?php
require_once('../Connections/FGSP.php'); 
mysql_select_db($database_FGSP, $FGSP)or die(mysql_error());
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>File Uploading With PHP and MySql</title>
<link rel="stylesheet" href="style.css" type="text/css" />
</head>
<body>
<div id="header">
<label>File Uploading With PHP and MySql</label>
</div>
<div id="body">
 <table width="80%" border="1">
    <tr>
    <th colspan="4">your uploads...<label><a href="index.php">upload new files...</a></label></th>
    </tr>
    <tr>
    <td>File Name</td>
    <td>File Type</td>
    <td>File Size(KB)</td>
    <td>UID</td>
    <td>View</td>
    </tr>
    <?php
 $sql="SELECT * FROM tblsqdocuments";
 $result_set=mysql_query($sql);
 while($row=mysql_fetch_array($result_set))
 {
  ?>
        <tr>
        <td><?php echo $row['docName'] ?></td>
        <td><?php echo $row['docType'] ?></td>
        <td><?php echo $row['docSize'] ?></td>
        <td><?php echo $row['UID'] ?></td>
        <td><a href="uploads/<?php echo $row['docName'] ?>" target="_blank">view file</a></td>
        </tr>
        <?php
 }
 ?>
    </table>
    
</div>
</body>
</html>