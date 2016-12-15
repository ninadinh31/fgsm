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