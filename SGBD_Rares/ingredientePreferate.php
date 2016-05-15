<!DOCTYPE html>
<html>
<head>
	<title>Ingrediente preferate</title>
</head>
<body>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" >

		<label>Introduceti username-ul:
			<input type="text" name="username" >
		</label>
		<input type="submit" name='submit' value="Submit">
		<?php
			if(isset($_REQUEST['submit']))
			{	
				
				$username=$_REQUEST['username'];
				$mesaj="";
				
				$aux="(DESCRIPTION =
			    (ADDRESS = (PROTOCOL = TCP)(HOST = Ciprian_PC)(PORT = 1522))
			    (CONNECT_DATA =
			      (SERVER = DEDICATED)
			      (SERVICE_NAME = XE)
			    )
			  )";
			$conn = oci_connect("STUDENT", "STUDENT","localhost/XE") or die;
			if(empty($_REQUEST['username']))
			{
				echo "<p>Completeaza campul</p>";
			}
			else
			{
				$sql="DECLARE
							
					  BEGin
						:mesaj:=ingrediente_preferate(:username);
					  END;";
				$statement=oci_parse($conn, $sql);
				oci_bind_by_name($statement, ":username", $username,2000);
				oci_bind_by_name($statement, ":mesaj", $mesaj,2000);
				oci_execute($statement,OCI_DEFAULT);
				oci_commit($conn);
				oci_free_statement($statement);
				echo "<p>".$mesaj ."</p>";
			}
			}
		?>
		
		
	</form>
</body>
</html>