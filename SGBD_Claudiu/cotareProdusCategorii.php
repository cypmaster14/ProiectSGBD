<?php
	$connect = oci_pconnect("STUDENT", "STUDENT", "//localhost/XE:pooled");
	$command1=oci_parse($connect,'begin :rezultat :=cotareProdusPeCategorii (); end;');
	oci_bind_by_name($command1,':rezultat',$rezultat,5000);
	oci_execute($command1);
	$e = oci_error($command1);
	if($e){
		print $e['message'];
	}
	else{
		echo $rezultat;
	}
?>