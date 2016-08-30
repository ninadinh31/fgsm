<body>

<h1>Forgot Username?</h1>
<p>Use your email address to get a message sent to you with your username in it.</p>
<div class="entrybox" style="text-align:center, border-style:solid">
	<form action="forgotusernamecheckemails.php" method="POST">
		Email Address:<br><input type="text" name="email"><br>
		Confirm Email Address:<br><input type="text" name="emailconfirm"><br><br>
		<input type="submit" value="Submit">
		<?php header("forgotusernamecheckemails.php"); ?>
	</form>
</div>

</body>

