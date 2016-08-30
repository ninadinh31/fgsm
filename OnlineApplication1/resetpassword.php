<body>

<h1>Reset Password</h1>
<p>Use your username to reset your password.</p>
<div class="entrybox" style="text-align:center, border-style:solid">
	<form action="resetpasswordform.php" method="POST">
		Username:<br><input type="text" name="username"><br><br>
		<input type="submit" value="Submit">
		<?php header("resetpasswordform.php"); ?>
	</form>
</div>

</body>
