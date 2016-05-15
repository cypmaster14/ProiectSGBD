<?php
	$conn=oci_connect("EDEC","edec","localhost/XE");
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	echo '<div id="Sign-In">
		<fieldset style="width:20%" class="center">
		<form method="POST" action="resetPassword.php">
		Old password: <br><input type="password" name="oldpass" size="40"><br>
		New password: <br><input type="password" name="newpass" size="40"><br>
		Confirm password: <br><input type="password" name="confpass" size="40"><br><br>
		<center><input id="button" type="submit" name="submit" value="Submit"></center>
		</form>
		</fieldset>
		</div>';
	function resetPassword($conn)
	{
		session_start();
		if($_POST['oldpass']!='' and $_POST['newpass']!='' and $_POST['confpass']!='')
		{
			if (strcmp($_POST['newpass'],$_POST['confpass'])==0){
				$command=oci_parse($conn,'begin schimbareParola(\'claudiu95\',:old_pass,:new_pass);end;');
				oci_bind_by_name($command,':old_pass',$_POST['oldpass']);
				oci_bind_by_name($command,':new_pass',$_POST['newpass']);
				@oci_execute($command);
				$e = oci_error($command);
				if($e){
					echo "<p><center>".$e['message']."</center></p>";
				}
				else {
					echo "<p><center>Totul e ok.</center></p>";
				}
			}
			else{
				echo "<p><center>Parolele nu corespund.</center></p>";
			}
		}
		else {
			echo "<p><center>Completati toate campurile.</center></p>";
		}
	}
	if(isset($_POST['submit']))
	{
		resetPassword($conn);
	}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Reset Password</title>
<link rel="stylesheet" type="text/css">
<style>
#Sign-In{
margin-top:150px;
margin-bottom:0px;
margin-right:150px;
margin-left:450px;
padding:9px 35px;
border-radius:20px;
}
</style>
</head>
<body id="body-color">

</body>
</html> 