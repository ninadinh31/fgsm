<h3 align="center">Internship Interests</h3>
            <table align="center" width="491" border="1">
              <tbody>
                <tr>
                  <td width="254"><b>General Internship Interest</b></td>
                  <td width="221"><?php echo $row_rsApplication['InternshipInterest']; ?></td>
                </tr>
                <tr>
                  <td><b>Past Student Internship</b></td>
                  <td><?php echo $row_rsApplication['PastInternLocation']; ?></td>
                </tr>
                <tr>
                  <td><b>Interning in Fall 2016?</b></td>
                  <td><?php echo $row_rsApplication['InternInFall']; ?></td>
                </tr>
                <tr>
                  <td><b>If interning in Fall 2016, please specify location</b></td>
                  <td><?php echo $row_rsApplication['FallInternLoc']; ?></td>
                </tr>
                <tr>
                  <td><b>Other comments on your interest or passion</b></td>
                  <td><?php echo $row_rsApplication['OtherComments']; ?></td>
                </tr>
              </tbody>
            </table>
            <p align="center">
              <input name="ButAddIntInterest" type="button" id="ButAddIntInterest" onClick="MM_goToURL('parent','InternshipInterest1.php');return document.MM_returnValue" value="Add/Edit Internship Interest">
            </p>