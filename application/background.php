<?php



?>

<h3>Background</h3>
<table class="table table-bordered">
	<tbody>
		<tr>
        	<td colspan="2">
            	<div class="form-group">
                	<label for="credits">Credits Earned:</label>
                    <input type="text" class="form-control" value="<?php echo $row_rsApplication['Credits']; ?>" id="credits">
				</div>
            </td>
			<td colspan="2">
            	<div class="form-group">
  					<label for="year">Year:</label>
  					<select class="form-control" id="year">
    					<option>Freshman</option>
                        <option>Sophomore</option>
                        <option>Junior</option>
                        <option>Senior</option>
  					</select>
				</div>
			</td>
		</tr>
		<tr>
			<td colspan="2">            	
            	<div class="form-group">
  					<label for="gender">Gender:</label>
  					<select class="form-control" id="gender">
    					<option>Female</option>
                        <option>Male</option>
  					</select>
				</div>
            </td>
            <td colspan="2">
            	<div class="form-group">
                	<label for="graddate">Anticipated Graduation Date:</label>
                    <input type="date" class="form-control" 
                    value="<?php echo date("YYYY-MM-DD", strtotime($row_rsApplication['Graduation']));?>" id="graddate" >
                </div>
            </td>
		</tr>
		<tr>
			<td colspan="2">
            	<div class="form-group">
                	<label for="overallgpa">Overall GPA:</label>
                    <input type="text" class="form-control" value="<?php printf("%.2f", $row_rsApplication['OverallGPA']); ?>" 
                    id="overallgpa">
				</div>
            </td>
            <td colspan="2">
            	<div class="form-group">
                	<label for="majorgpa">Major GPA:</label>
                    <input type="text" class="form-control" value="<?php printf("%.2f", $row_rsApplication['MajorGPA']); ?>" 
                    id="majorgpa">
				</div>
            </td>
		</tr>
	</tbody>
</table>
<input name="save" type="button" class="btn btn-primary" id="savebutton" onClick="" value="Save">
<input name="next" type="button" class="btn btn-primary" id="nextbutton" value="Save & Next"> 
