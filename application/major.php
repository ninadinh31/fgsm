<h3 align="center">Major</h3>
<table width="493" border="3" align="center">
  <tbody>
    <tr>
      <td width="259">
        <strong>Program
          <input name="UID" type="hidden" id="UID" value="<?php echo $row_rsMajor['UID']; ?>">
          <input name="MID" type="hidden" id="MID" value="<?php echo $row_rsMajor['MID']; ?>">
      </strong>
  </td>
  <td width="106"><strong>Type</strong></td>
  <td width="106">&nbsp;</td>
</tr>
<?php 
do { 
  ?>
  <tr>
    <td><?php echo $row_rsMajor['Programs']; ?></td>
    <td><?php echo $row_rsMajor['Type']; ?></td>
    <td><a href="DeleteMajor1.php?UID=<?php echo $row_rsMajor['UID'];?> && MID=<?php echo $row_rsMajor['MID'];?>">Delete Major</a></td>
</tr>
<?php } while ($row_rsMajor = mysql_fetch_assoc($rsMajor)); ?>
</tbody>
</table>
<p align="center">
    <input name="Add Major" type="button" id="Add Major" onClick="MM_goToURL('parent','Majors1.php');return document.MM_returnValue" value="Add Major">
    <input name="Editbutton" type="button" id="Editbutton" onClick="MM_goToURL('parent','OtherMajor1.php');return document.MM_returnValue" value="Edit Other Major">
</p>