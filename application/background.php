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