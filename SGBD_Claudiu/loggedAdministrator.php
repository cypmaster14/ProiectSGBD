<?php
	session_start();
	echo "<h1>Aveti voie sa executati procedurile/functiile : specifice unui Administrator</h1>";
?>
<form method="POST">
 <pre>
 <p> UPDATE PRODUS </p>
 Codul de bare al produsului: <input type="text" name="barcode" ><br>
 <input type="submit" value="Search">
 </pre>
 </form>
 
<?php
	if(isset($_POST['barcode'])){
		$connect = oci_pconnect("STUDENT", "STUDENT", "//localhost/XE:pooled");
		$commandSearch=oci_parse($connect,'begin  select count(*) into :rezultat from product where barcode=:barcode; end;');
		oci_bind_by_name($commandSearch,':rezultat',$rezultat);
		oci_bind_by_name($commandSearch,':barcode',$_POST['barcode']);
		oci_execute($commandSearch);
		if($rezultat>0){
			session_start();
			$_SESSION=array_merge($_SESSION,$_POST);
            session_write_close();
			header("Location: http://localhost/SGBD/productFound.php");
		}
		else{
			echo "Acest produs nu exista in baza de date!";
		}
	}
		
?>