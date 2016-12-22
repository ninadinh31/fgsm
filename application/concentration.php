<?php 

$query = sprintf("SELECT Concentration FROM tblsqConcentration", GetSQLValueString($colname_concentrations, "text"));
$concentrationsList = mysql_query($query, $localhost) or die(mysql_error());
$row_concentrations = mysql_fetch_assoc($concentrationsList);

?>

<h3>Rank of Concentrations</h3>
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td><strong>Concentration</strong></td>
                  <td><strong>Rank</strong></td>
                </tr>
                <?php 
                  do { 
                ?>
                  <tr>
                    <td><?php echo $row_concentrations['Concentration']; ?></td>
                    <td></td>
                  </tr>
                <?php 
                  } while ($row_concentrations = mysql_fetch_assoc($concentrationsList)); 
                ?>
              </tbody>
            </table>