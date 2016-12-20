<?php



?>

<h3>Background</h3>
<table class="table">
	<tbody>
		<tr>
			<td width="111"><strong>Seniority</strong></td>
			<td width="219">
				<?php 
				echo $row_rsApplication['Seniority'];
				?>
			</td>
			<td width="183"><strong>Citizenship</strong></td>
			<td width="446">
				<?php 
				echo $row_rsApplication['Citizenship'];
				?>
			</td>
		</tr>
		<tr>
			<td><strong>Gender</strong></td>
			<td>
				<?php 
				echo $row_rsApplication['Gender']; 
				?>
			</td>
			<td rowspan="2"><strong>General Internship Interest</strong></td>
			<td rowspan="2">
				<?php 
				echo $row_rsApplication['InternshipInterest']; 
				?>
			</td>
		</tr>
		<tr>
			<td><strong>Race</strong></td>
			<td>
				<?php 
				echo $row_rsApplication['Race']; 
				?>
			</td>
		</tr>
		<tr>
			<td><strong>Overall GPA</strong></td>
			<td>
				<?php 
				printf("%.2f", $row_rsApplication['OverallGPA']); 
				?>
			</td>
			<td rowspan="2"><strong>Past Students Internship</strong></td>
			<td rowspan="2">
				<?php 
				echo $row_rsApplication['PastInternLocation']; 
				?>
			</td>
		</tr>
		<tr>
			<td><strong>Major GPA</strong></td>
			<td>
				<?php 
				printf("%.2f", $row_rsApplication['MajorGPA']); 
				?>
			</td>
		</tr>
		<tr>
			<td><strong>Credits</strong></td>
			<td>
				<?php 
				echo $row_rsApplication['Credits']; 
				?>
			</td>
			<td><strong>Interning in Fall 2016?</strong></td>
			<td>
				<?php 
				echo $row_rsApplication['InternInFall']; 
				?>
			</td>
		</tr>
		<tr>
			<td><strong>Graduation Date</strong></td>
			<td>
				<?php 
				echo date("m/d/Y", strtotime($row_rsApplication['Graduation'])); 
				?>
			</td>
			<td rowspan="2"><strong>If Interning in Fall, please specify location</strong></td>
			<td rowspan="2">
				<?php 
				echo $row_rsApplication['FallInternLoc']; 
				?>
			</td>
		</tr>
	</tbody>
</table>
<input name="EditBackground" type="button" class="btn btn-primary" id="EditBackground" onClick="MM_goToURL('parent','EditBackground1.php');return document.MM_returnValue" value="Edit Background"> 
<br>
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