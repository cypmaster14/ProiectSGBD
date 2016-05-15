<?php
	echo "<h1> Creeaza o campanie! </h1>";
	$connect = oci_pconnect("STUDENT", "STUDENT", "//localhost/XE:pooled");
?>

<form method="POST" >
 <pre>
	Numele campaniei:      <input type="text" name="numeCampanie"> <br>
	Descrierea campaniei:  <input type="text" name="descriereCampanie">  <br>
    Barcode-ul produsului:     <input type="texT" name="barcode"> <br>
       <input type="submit" value="Creeaza campanie">
 </pre>
</form>

<?php	
	if(isset($_POST['numeCampanie']) && isset($_POST['descriereCampanie']) && isset($_POST['barcode'])){
		$command1=oci_parse($connect,'begin creeazaCampanie(:numeCampanie, :descriereCampanie, :barcode); end;');
		oci_bind_by_name($command1,':numeCampanie',$_POST['numeCampanie']);
		oci_bind_by_name($command1,':descriereCampanie',$_POST['descriereCampanie']);
		oci_bind_by_name($command1,':barcode',$_POST['barcode']);
		oci_execute($command1);
		$e = oci_error($command1);
		if($e){
			print $e['message'];
		}
		else{
			echo "<h1> Campanie creata cu succes! </h1>";
		}
	}
?>