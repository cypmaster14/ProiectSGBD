<!DOCTYPE html>
<html>
<head>
	<title>Sterge Produs</title>
</head>
<body>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" >

		<label>Introduceti Barcode-ul produsului:
			<input type="text" name="barcode" >
		</label>
		<input type="submit" name='sterge' value="Sterge">
		<?php
			if(isset($_REQUEST['sterge']))
			{	
				
				$barcode=$_REQUEST['barcode'];
				$mesaj="";
				
				$aux="(DESCRIPTION =
			    (ADDRESS = (PROTOCOL = TCP)(HOST = Ciprian_PC)(PORT = 1522))
			    (CONNECT_DATA =
			      (SERVER = DEDICATED)
			      (SERVICE_NAME = XE)
			    )
			  )";
			$conn = oci_connect("STUDENT", "STUDENT","localhost/XE") or die;
			if(empty($_REQUEST['barcode']))
			{
				echo "<p>Completeaza campul</p>";
			}
			else
			{
				$sql="DECLARE
							
					  BEGin
						:mesaj:=stergereprodus(:barcode);
					  END;";
				$statement=oci_parse($conn, $sql);
				oci_bind_by_name($statement, ":barcode", $barcode,2000);
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