<?php
    session_start();
	$connect = oci_pconnect("STUDENT", "STUDENT", "localhost/XE:");
	if($connect){
		echo "<center><h1>Inregistrati un cont nou.</h1></center>";
	}
	else{
		echo "conexiune nereusita";
	}
?>

<pre>
<center>
<form method="POST" >
		Email:<input type="text" name="username"> 
		Parola:<input type="text" name="parola">  
		Prenume:<input type="text" name="fname">
		Nume:<input type="text" name="lname">
		Grad: <select name="grad" > <option value="user"> User </option> <option value="administrator"> Administrator </option> </select>
		
		
   <input type="submit" value="Register" >
</form>
<pre>
</center>
<?php
	if (isset($_POST['username']) && isset($_POST['parola']) && isset ($_POST['grad']) && isset($_POST['fname']) && isset($_POST['lname'])){
		$command1=oci_parse($connect,'begin inregistrareUtilizator(:username, :parola,:fname,:lname,:grad); end;');
		oci_bind_by_name($command1,':username',$_POST['username']);
		oci_bind_by_name($command1,':parola',$_POST['parola']);
		oci_bind_by_name($command1,':grad',$_POST['grad']);
		oci_bind_by_name($command1,':fname',$_POST['fname']);
		oci_bind_by_name($command1,':lname',$_POST['lname']);
		@oci_execute($command1);
		$e = oci_error($command1);
				if(isset($e['message'])){
					echo "<p><center>"."Utilizatorul exista deja"."</center></p>";
				}
				else {
					echo "<p><center>Utilizator inregistrat</center></p>";
				}
		}
?>