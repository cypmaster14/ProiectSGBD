


<!DOCTYPE html>
<html>
<head>
	<title>StergeUtilizator</title>
</head>
<body>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" >

		<label>Introduce-ti email-ul utilizatorului:
			<input type="text" name="utilizator" >
		</label>
		<input type="submit" name='sterge' value="Sterge">
		<?php
			if(isset($_REQUEST['sterge']))
			{	
				
				$utilizator=$_REQUEST['utilizator'];
				$mesaj="";
				
				$aux="(DESCRIPTION =
			    (ADDRESS = (PROTOCOL = TCP)(HOST = Ciprian_PC)(PORT = 1522))
			    (CONNECT_DATA =
			      (SERVER = DEDICATED)
			      (SERVICE_NAME = XE)
			    )
			  )";
			$conn = oci_connect("STUDENT", "STUDENT",$aux) or die;
			if(empty($_REQUEST['utilizator']))
			{
				echo "<p>Completeaza campul</p>";
			}
			else
			{

				$sql="DECLARE
							
					  BEGin
						:mesaj:=stergereutilizator(:email);
					  END;";
				$statement=oci_parse($conn, $sql);
				oci_bind_by_name($statement, ":email", $utilizator,2000);
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