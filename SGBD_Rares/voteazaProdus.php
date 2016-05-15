<!DOCTYPE html>
<html>
<head>
	<title>Voteaza Produs</title>
</head>
<body>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" >

		<label>Introduceti barcode-ul produsului:
			<input type="text" name="barcode" >
		</label> <br>
		Selectati rating-ul produsului: 
		<select name="rating">
			<option>1</option>
			<option>2</option>
			<option>3</option>
			<option>4</option>
			<option>5</option>
		</select> <br>
		<input type="submit" name='voteaza' value="voteaza">
		<?php
			if(isset($_REQUEST['voteaza']))
			{	
				
				$barcode=$_REQUEST['barcode'];
				$rating=$_REQUEST['rating'];
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
				echo "<p>Completeaza campurile!</p>";
			}
			else
			{
				$sql="DECLARE
							
					  BEGIN
						:mesaj:=voteazaProdus(:barcode,:rating);
					  END;";
				$statement=oci_parse($conn, $sql);
				oci_bind_by_name($statement, ":barcode", $barcode,2000);				
				oci_bind_by_name($statement, ":rating", $rating,2000);
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