<?php
	
	


?>

<!DOCTYPE html>
<html>
<head>
	<title>Top 10</title>
</head>
<body>
	<form action="<?php echo $_SERVER['PHP_SELF'] ?>" method="post" >
		
		<input type="submit" name="afiseaza" value="Top10">
		<?php
			$aux="(DESCRIPTION =
			    (ADDRESS = (PROTOCOL = TCP)(HOST = Ciprian_PC)(PORT = 1522))
			    (CONNECT_DATA =
			      (SERVER = DEDICATED)
			      (SERVICE_NAME = XE)
			    )
			  )";
			$conn = oci_connect("STUDENT", "STUDENT",$aux) or die;
			$categorii="";
			$sql="declare
				  begin
					for v_i in (select category_name from edeccategory) LOOP
						:mesaj:=:mesaj||v_i.category_name||'$';
					END loop;
					:mesaj:=substr(:mesaj,1,length(:mesaj)-1);
				  end;";
			$statement=oci_parse($conn, $sql);
			oci_bind_by_name($statement, ":mesaj", $mesaj,2000);
			oci_execute($statement,OCI_DEFAULT);
			oci_commit($conn);
			oci_free_statement($statement);
			$sir=explode('$', $mesaj);
			echo "<p>Selectati Categoria</p>";
			echo "<select name='categorie'>";
			foreach($sir as $elem)
			{
				echo "<option value=".$elem.">".$elem."</option>"; 
			}


			echo "</select>";
			if(isset($_REQUEST['afiseaza']))
			{	
				
				$categorie=$_REQUEST['categorie'];
				$mesaj="";
				
				$aux="(DESCRIPTION =
			    (ADDRESS = (PROTOCOL = TCP)(HOST = Ciprian_PC)(PORT = 1522))
			    (CONNECT_DATA =
			      (SERVER = DEDICATED)
			      (SERVICE_NAME = XE)
			    )
			  )";
			$conn = oci_connect("STUDENT", "STUDENT",$aux) or die;
			
				$sql="declare
					 
					begin  
					  :mesaj:=top10(:categorie);					  
					end;";
				$statement=oci_parse($conn, $sql);
				oci_bind_by_name($statement, ":categorie", $categorie,2000);
				oci_bind_by_name($statement, ":mesaj", $mesaj,2000);
				oci_execute($statement,OCI_DEFAULT);
				oci_commit($conn);
				oci_free_statement($statement);
				$sir=explode("$", $mesaj);
				echo "<ol>";
				for($x=0;$x<count($sir);$x++)
				{
					echo"<li>".$sir[$x]."</li>";
				}

				echo "</ol>";
				
			

			}
		?>
		
		
	</form>
</body>
</html>