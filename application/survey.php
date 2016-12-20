<h3 align="center">Survey</h3>
            <p align="center"><i>How did you hear about the Federal Semester and Global Semester Programs?</i></p>
            <table align="center" width="1007" border="1">
              <tbody>
                <tr>
                  <td width="219"><strong>Method</strong></td>
                  <td width="273"><strong>Details</strong></td>
                  <td width="119">UID</td>
                  <td width="156">PgmYear</td>
                  <td width="206">&nbsp;</td>
                </tr>
                <?php do { ?>
                <tr>
                  <td><?php echo $row_rsMarketing['MarketingType']; ?></td>
                  <td><?php echo $row_rsMarketing['MarketingLocation']; ?></td>
                  <td><?php echo $row_rsMarketing['UID']; ?></td>
                  <td><?php echo $row_rsMarketing['PgmYear']; ?></td>
                  <td><a href="DeleteMarketing1.php?UID=<?php echo $row_rsMarketing['UID'];?> && PgmYear=<?php echo $row_rsMarketing['PgmYear'];?> && MarketingID=<?php echo $row_rsMarketing['MarketingID'];?>">Delete</a></td>
                </tr>
                <?php } while ($row_rsMarketing = mysql_fetch_assoc($rsMarketing)); ?>
              </tbody>
            </table>
            <p align="center"><input name="subAddMarketing" type="submit" id="subAddMarketing" onClick="MM_goToURL('parent','Marketing1.php');return document.MM_returnValue" value="Add Survey"></p>
            <p>&nbsp;</p>
            <h3>Citizenship</h3>
<table class="table">
  <thead>
    <th>Country</th>
    <th>&nbsp;</th>
  </thead>
  <tbody>
    <?php 
    do { 
      ?>
      <tr>
        <td width="363"><?php echo $row_rsCitizenshipList['Country']; ?>
          <input name="hiddenField" type="hidden" id="hiddenField" value="<?php echo $row_rstViewRegistration['UserID']; ?>"></td>
          <td width="116">
            <a href="DeleteCountry1.php?UID=<?php echo $row_rsCitizenshipList['UID'];?> && CountryID=<?php echo 
              $row_rsCitizenshipList['CountryID'];?>">
              Delete Country
            </a>
          </td>
        </tr>
        <?php 
      } while ($row_rsCitizenshipList = mysql_fetch_assoc($rsCitizenshipList));
      ?>
    </tbody>
  </table>
  <input name="AddCitizenship" type="button" class="btn btn-primary" onClick="MM_goToURL('parent','Citizenship1.php');return document.MM_returnValue" value="Add Citizenship"> 
  <br>
  <h3>Language Skills (if any)</h3>
  <?php 
  do { 
    ?>
    <table class="table">
      <thead>
        <th>Language</th>
        <th>&nbsp;</th>
      </thead>
      <tbody>
        <tr>
          <td width="365"><?php echo $row_rsLanguages['Language']; ?>
            <input name="hiddenUID" type="hidden" id="hiddenUID" value="<?php echo $row_rsLanguages['UID']; ?>"></td>
            <td width="118">&nbsp;</td>
          </tr>
        </tbody>
      </table>
      <?php 
    } while ($row_rsLanguages = mysql_fetch_assoc($rsLanguages)); 
    ?>
    <input name="butAddLanguage" type="button" class="btn btn-primary" onClick="MM_goToURL('parent','AddLanguage1.php');return document.MM_returnValue" value="Add Language">
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
    <td><a href="DeleteUniversityPrograms1.php?UID=<?php echo $row_rsViewUNIVProgram['UID'];?> && LID=<?php echo    $row_rsViewUNIVProgram['LID'];?>">Delete Program</a></td>
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