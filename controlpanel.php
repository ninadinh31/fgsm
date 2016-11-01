<?php 

require_once('Connections/NewLogin.php'); 

// *** Validate request to login to this site.
if (!isset($_SESSION)) {
  session_start();
}

// /**
//  * Determines if the current user is authorized to access the login page
//  */
// function isAuthorized($strUsers, $strGroups, $UserName, $UserGroup) { 
// 	// For security, start by assuming the visitor is NOT authorized. 
// 	$isValid = False; 

// 	// When a visitor has logged into this site, the Session variable MM_Username set equal to their username. 
// 	// Therefore, we know that a user is NOT logged in if that Session variable is blank. 
// 	if (!empty($UserName)) { 
//   		// Besides being logged in, you may restrict access to only certain users based on an ID established when they login.   
//   		// Parse the strings into arrays. 
//   		$arrUsers = Explode(",", $strUsers); 
//   		$arrGroups = Explode(",", $strGroups); 
//   		if (in_array($UserName, $arrUsers)) { 
//     		$isValid = true; 
//   		} 
  		
//   		// Or, you may restrict access to only certain users based on their username. 
//   		if (in_array($UserGroup, $arrGroups)) { 
//     		$isValid = true; 
//   		} 
  		
//   		if (($strUsers == "") && false) { 
//     		$isValid = true; 
//   		} 
// 	} 
	
// 	return $isValid; 
// }

// $MM_restrictGoTo = "login.php";
// if (!((isset($_SESSION['MM_Username'])) && (isAuthorized("",$MM_authorizedUsers, $_SESSION['MM_Username'], $_SESSION['MM_UserGroup'])))) {   
// 	$MM_qsChar = "?";
// 	$MM_referrer = $_SERVER['PHP_SELF'];
	
// 	if (strpos($MM_restrictGoTo, "?")) 
// 		$MM_qsChar = "&";
    
//     if (isset($_SERVER['QUERY_STRING']) && strlen($_SERVER['QUERY_STRING']) > 0) 
//     	$MM_referrer .= "?" . $_SERVER['QUERY_STRING'];
    
//     $MM_restrictGoTo = $MM_restrictGoTo. $MM_qsChar . "accesscheck=" . urlencode($MM_referrer);
//     echo "You don't have access to this page.";
//     header("Location: ". $MM_restrictGoTo); 
//     exit;
// }

require_once('includes/header.php');

echo $_SESSION;

?>

<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default">
			<div class="panel-heading">Control Panel</div>
			<div class="panel-body">
				<a href="events.php">Federal & Global Fellows Events</a><br>
				<a href="addevents.php">Admin Add Event</a><br>
				<a href="editevents.php">Admin Edit/Delete Event</a><br>
				<a href="">Internship Opportunities</a><br>
				<a href="">Federal & Global Fellows Application</a><br>
			</div>
		</div>
	</div>
</div>

</body>
</html>