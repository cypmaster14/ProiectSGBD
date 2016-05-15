<?php
	session_start();
	$barcode=0;
	if(isset($_REQUEST['barcode']))
	{
		$barcode=$_REQUEST['barcode'];
		$mesaj="";
		$name='';
		$quantity='';
		$price='';
		$rating='';
		$image='';

		$aux="(DESCRIPTION =
			    (ADDRESS = (PROTOCOL = TCP)(HOST = Ciprian_PC)(PORT = 1522))
			    (CONNECT_DATA =
			      (SERVER = DEDICATED)
			      (SERVICE_NAME = XE)
			    )
			  )";
		$conn = oci_connect("STUDENT", "STUDENT",$aux) or die;
		

		$sql="declare
				v_mesaj varchar2(2000);
			  begin
				v_mesaj:=cautareProdus(:barcode);
				:mesaj:=v_mesaj;
			  end;";
		$statement=oci_parse($conn, $sql);
		oci_bind_by_name($statement, ":mesaj", $mesaj,2000);
		oci_bind_by_name($statement, ":barcode", $barcode,2000);		
		oci_execute($statement,OCI_DEFAULT);
		oci_free_statement($statement);
		$vector=explode("$", $mesaj);
		if(count($vector>2))
		{	
			echo "<p>Nume:".$vector[0]."</p>";
			echo "<p>Greutate:".$vector[1]."</p>";
			echo "<p>Pret:".$vector[2]."</p>";
			echo "<p>Rating:".$vector[3]."</p>";
			echo "<img src=".$vector[4]. "alt='poza'>"."</p>";
			echo "<p>Ingrediente:</p>";
			echo "<ul>";
			for($x=5;$x<count($vector);$x++)
			{
				echo "<li>".$vector[$x]."</li>";
			}
			echo "</ul>";
		}

		


	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Produs</title>
	<link rel="stylesheet" href="stil.css">
</head>
<body>

</body>
</html>