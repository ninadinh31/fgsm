<h3 align="center">University Programs</h3>
<table width="893" border="1px solid black" align="center">
  <tbody>
    <tr>
      <td width="498"><strong>University Programs
        <input name="UID" type="hidden" id="UID" value="<?php echo $row_rsViewUNIVProgram['UID']; ?>">
        <input name="LID" type="hidden" id="LID" value="<?php echo $row_rsViewUNIVProgram['LID']; ?>">
    </strong></td>
    <td width="379">&nbsp;</td>
</tr>
<?php 
do { 
  ?>
  <tr>
    <td><?php echo $row_rsViewUNIVProgram['UPrograms']; ?></td>
    <td><a href="DeleteUniversityPrograms1.php?UID=<?php echo $row_rsViewUNIVProgram['UID'];?> && LID=<?php echo 	  $row_rsViewUNIVProgram['LID'];?>">Delete Program</a></td>
</tr>
<?php 
} while ($row_rsViewUNIVProgram = mysql_fetch_assoc($rsViewUNIVProgram)); 
?>
</tbody>
</table>
<p align="center">
  <input name="Add University Programs" type="button" id="AddUniPrograms" onClick="MM_goToURL('parent','UniversityPrograms.php');return document.MM_returnValue" value="Add University Programs">
</p>
<br>
<h3 align="center">Scholarships</h3>
<table align="center" width="892" border="1">
  <tbody>
    <tr>
      <td width="502"><strong>Scholarship(s)
        <input name="UID" type="hidden" id="UID" value="<?php echo $row_rsViewScholarship['UID']; ?>">
        <input name="ScholarshipID" type="hidden" id="ScholarshipID" value="<?php echo $row_rsViewScholarship['ScholarshipID']; ?>">
    </strong>
</td>
<td width="232"><strong>Year</strong></td>
<td width="136">&nbsp;</td>
</tr>
<?php do { ?>
<tr>
    <td><?php echo $row_rsViewScholarship['Scholarship']; ?></td>
    <td><?php echo $row_rsViewScholarship['ScholarshipYear']; ?></td>
    <td><a href="DeleteScholarship1.php?UID=<?php echo $row_rsViewScholarship['UID'];?> && ScholarshipID=<?php echo $row_rsViewScholarship['ScholarshipID'];?>">Delete Scholarship</a></td>
</tr>
<?php } while ($row_rsViewScholarship = mysql_fetch_assoc($rsViewScholarship)); ?>
</tbody>
</table>
<p align="center">
  <input name="butAddScholarship" type="button" id="butAddScholarship" onClick="MM_goToURL('parent','AddScholarship1.php');return document.MM_returnValue" value="Add Scholarship">
</p>
<br>
<h3 align="center">Study Abroad Experience</h3>
<table align="center" width="895" border="1">
  <tbody>
    <tr>
      <td width="294">
        <strong>Institution/Location
          <input name="StudyAID" type="hidden" id="StudyAID" value="<?php echo $row_rsStudyAbroad['StudyAID']; ?>">
      </strong>
  </td>
  <td width="246"><strong>Country</strong></td>
  <td width="190"><strong>Year</strong></td>
  <td width="137">&nbsp;</td>
</tr>
<?php do { ?>
<tr>
  <td><?php echo $row_rsStudyAbroad['Institution']; ?></td>
  <td><?php echo $row_rsStudyAbroad['Country']; ?></td>
  <td><?php echo $row_rsStudyAbroad['StudyYear']; ?></td>
  <td><a href="DeleteStudyAbroad.php?StudyAID=<?php echo $row_rsStudyAbroad['StudyAID'];?>">Delete Study Abroad</a></td>
</tr>
<?php } while ($row_rsStudyAbroad = mysql_fetch_assoc($rsStudyAbroad)); ?>
</tbody>
</table>
<p align="center">
  <input name="butAddStudyAbroad" type="button" id="butAddStudyAbroad" onClick="MM_goToURL('parent','AddStudyAbroad1.php');return document.MM_returnValue" value="Add Study Abroad">
</p>