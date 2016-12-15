<h3 align="center">Rank of Concentrations</h3>
            <table align="center" width="872" height="57" border="1">
              <tbody>
                <tr>
                  <td width="165">&nbsp;</td>
                  <td width="286"><strong>Concentration</strong></td>
                  <td width="147"><strong>Rank</strong></td>
                  <td width="246">&nbsp;</td>
                </tr>
                <?php 
                  do { 
                ?>
                  <tr>
                    <td><?php echo $row_rsConcentration['UID']; ?></td>
                    <td><?php echo $row_rsConcentration['Concentration']; ?></td>
                    <td><?php echo $row_rsConcentration['Rank']; ?></td>
                    <td><a href="DeleteConcentration1.php?RankID=<?php echo $row_rsConcentration['RankID'];?>">Delete Concentration</a></td>
                  </tr>
                <?php 
                  } while ($row_rsConcentration = mysql_fetch_assoc($rsConcentration)); 
                ?>
              </tbody>
            </table>
            <br>
            <p align="center">
              <input name="AddConcentration" type="button" id="AddConcentration" onClick="MM_goToURL('parent','Concentration1.php');return document.MM_returnValue" value="Add Concentration">
            </p>