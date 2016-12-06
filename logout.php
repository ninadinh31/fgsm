<?php 

	session_start();
	unsetSessionVariable('MM_UserGroup');
	unsetSessionVariable('MM_Username');
	session_destroy();

	header("Location: login.php");

	function unsetSessionVariable ($sessionVariableName) {
	   unset($GLOBALS[_SESSION][$sessionVariableName]);
	}

?>