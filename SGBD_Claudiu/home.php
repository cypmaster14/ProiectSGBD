<?php
    session_start();
	$connect = oci_pconnect("STUDENT", "STUDENT", "//localhost/XE:pooled");
	if($connect){
		echo "<h1>Bine ati venit!</h1> ";
	}
	else{
		echo "conexiune nereusita";
	}
?>

<pre>
<form method="POST" >
		USERNAME:<input type="text" name="username"> <select name="grad" > <option value="user"> User </option> <option value="administrator"> Administrator </option> </select>
		  PAROLA:<input type="text" name="parola">  
   <input type="submit" value="Login" >
</form>
<pre>
<?php
	if (isset($_POST['username']) && isset($_POST['parola']) && isset ($_POST['grad'])){
		$command1=oci_parse($connect,'begin :rezultat := logareValidaProiect(:username, :parola, :grad); end;');
		oci_bind_by_name($command1,':rezultat',$rezultat);
		oci_bind_by_name($command1,':username',$_POST['username']);
		oci_bind_by_name($command1,':parola',$_POST['parola']);
		oci_bind_by_name($command1,':grad',$_POST['grad']);
		oci_execute($command1);
		if($rezultat==1){
			$_SESSION['username']=$_POST['username'];
			$_SESSION['parola']=$_POST['parola'];
			$_SESSION['grad']=$_POST['grad'];
			if($_POST['grad']=="user"){
				header("Location: http://localhost/SGBD_Claudiu/loggedUser.php");
			}
			else{
				header("Location: http://localhost/SGBD_Claudiu/loggedAdministrator.php");
			}
		}
		else{
			echo "Nu ati introdus date valide!";
		}
	}
?>