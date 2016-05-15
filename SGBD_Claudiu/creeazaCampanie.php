<?php
	echo "<h1> Creeaza o campanie! </h1>";
?>

<form method="POST">
 <pre>
	Numele campaniei:      <input type="text" name="nume"> <br>
	Descrierea campaniei:  <input type="text" name="descriere">  <br>
    Barcode-ul produsului:     <input type="texT" name="barcode"> <br>
       <input type="submit" value="Creeaza campanie">
 </pre>
</form>
<?php
	//var_dump($_POST);
	$connect2 = oci_pconnect("STUDENT", "STUDENT", "//localhost/XE:pooled");
	if(isset($_POST['nume']) && isset($_POST['descriere']) && isset($_POST['barcode'])){
		$command1=oci_parse($connect2,'insert into Campaing values(null, :nume, :descriere, :barcode)');
		oci_bind_by_name($command1,':nume',$_POST['nume']);
		oci_bind_by_name($command1,':descriere',$_POST['descriere']);
		oci_bind_by_name($command1,':barcode',$_POST['barcode']);
		oci_execute($command1);
		$e = oci_error($command1);
		if($e){
			print $e['message'];
		}
			else{
			echo "<h1> Campanie creata cu success! </h1>";
		}
		
		
	}
?>