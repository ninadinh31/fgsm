<h3 align="center">Supplementary Courses</h3>
            <table align="center" width="883" border="1">
              <tbody>
                <tr>
                  <td width="240"><strong>Supplementary Courses</strong></td>
                  <td width="230"><strong>Course Code</strong></td>
                  <td width="197"><strong>UID</strong></td>
                  <td width="188">&nbsp;</td>
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
              <input name="AddSupplementary" type="button" id="AddSupplementary" onClick="MM_goToURL('parent','Supplementary1.php');return document.MM_returnValue" value="Add Supplementary Course">
              </p>