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