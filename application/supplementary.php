<?php

$query = sprintf("SELECT CourseID, CourseTitle FROM tblsqSupplementaryCourses", GetSQLValueString($colname_concentrations, "text"));
$concentrationsList = mysql_query($query, $localhost) or die(mysql_error());
$row_concentrations = mysql_fetch_assoc($concentrationsList);

?>

<h3>Supplementary Courses</h3>
            <table class="table table-bordered">
              <tbody>
                <tr>
                  <td><strong>Supplementary Courses</strong></td>
                  <td><strong>Course Code</strong></td>
                  <td><strong>UID</strong></td>
                  <td>&nbsp;</td>
                </tr>
                <?php do { ?>
                <tr>
                  <td><?php echo $row_rsSupplementaryCourses['CourseTitle']; ?></td>
                  <td><?php echo $row_rsSupplementaryCourses['CourseID']; ?></td>
                  <td><?php echo $row_rsSupplementaryCourses['UID']; ?></td>
                  <td><a href="DeleteSupplementaryCrs1.php?UID=<?php echo $row_rsSupplementaryCourses['UID'];?> && CourseID=<?php echo $row_rsSupplementaryCourses['CourseID'];?>">Delete Course</a></td>
                </tr>
                <?php } while ($row_rsSupplementaryCourses = mysql_fetch_assoc($rsSupplementaryCourses)); ?>
              </tbody>
            </table>
            <p align="center">
              <input name="AddSupplementary" type="button" id="AddSupplementary" class="btn btn-primary" onClick="MM_goToURL('parent','Supplementary1.php');return document.MM_returnValue" value="Add Supplementary Course">
              </p>