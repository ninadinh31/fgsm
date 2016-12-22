<h3>Major</h3>
<table class="table table-bordered">
  <tbody>
    <tr>
      <td>
        <strong>Program
          <input name="UID" type="hidden" id="UID" value="<?php echo $row_rsMajor['UID']; ?>">
          <input name="MID" type="hidden" id="MID" value="<?php echo $row_rsMajor['MID']; ?>">
      </strong>
  </td>
  <td><strong>Type</strong></td>
  <td>&nbsp;</td>
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
<p>
    <input name="Add Major" type="button" id="Add Major" class="btn btn-primary" onClick="MM_goToURL('parent','Majors1.php');return document.MM_returnValue" value="Add Major">
    <input name="Editbutton" type="button" id="Editbutton" class="btn btn-primary" onClick="MM_goToURL('parent','OtherMajor1.php');return document.MM_returnValue" value="Edit Other Major">
</p>