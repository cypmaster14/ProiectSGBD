<?php
	$conn=oci_connect("EDEC","edec","localhost/XE");
	if (!$conn) {
		$e = oci_error();
		trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
	}
	echo '<div id="Sign-In">
			<fieldset style="width:20%" class="center">
			<form method="POST" action="addProduct.php">
			Barcode: <br><input type="text" name="barcode" size="40"><br>
			Product Name: <br><input type="text" name="productName" size="40"><br>
			Quantity: <br><input type="text" name="quantity" size="40"><br>
			Price: <br><input type="text" name="price" size="40"><br>
			Image: <br><input type="text" name="image" size="40"><br>
			Category: <br><select name="categoryID"><option value=""></option>';
	$querry = oci_parse($conn,'SELECT * from EDECCATEGORY');
	oci_execute($querry);
	echo '<p>'.oci_num_rows($querry).'</p>';
	while($row = oci_fetch_array($querry, OCI_ASSOC+OCI_RETURN_NULLS)){
		echo '<option value="'.$row['CATEGORY_ID'].'">'.$row['CATEGORY_NAME'].'</option>';
	};
	echo '</select><br><br>
			<center><input id="button" type="submit" name="submit" value="Add Product"></center>
			</form>
			</fieldset>
			</div>';
	function addProduct($conn)
	{
		session_start();
		if($_POST['barcode']!='' and $_POST['productName']!='' and $_POST['quantity']!='' and $_POST['price']!='' and $_POST['image']!='' and $_POST['categoryID']!='')
		{
			$command=oci_parse($conn,'begin adaugareProdus(:barcode,:productName,:quantity,:price,:image,:categoryID);end;');
			oci_bind_by_name($command,':barcode',$_POST['barcode']);
			oci_bind_by_name($command,':productName',$_POST['productName']);
			oci_bind_by_name($command,':quantity',$_POST['quantity']);
			oci_bind_by_name($command,':price',$_POST['price']);
			oci_bind_by_name($command,':image',$_POST['image']);
			oci_bind_by_name($command,':categoryID',$_POST['categoryID']);
			@oci_execute($command);
			$e = oci_error($command);
			if($e){
				echo "<p><center>".$e['message']."</center><p>";
			}
			else {
				echo "<p><center>Produs adaugat cu succes.</center></p>";
			}
		}
		else {
			echo "<p><center>Completati toate campurile.</center></p>";
		}
	}
	if(isset($_POST['submit']))
	{
		addProduct($conn);
	}
?>

<!DOCTYPE HTML>
<html>
<head>
<title>Reset Password</title>
<link rel="stylesheet" type="text/css">
<style>
#Sign-In{
margin-top:50px;
margin-bottom:0px;
margin-right:150px;
margin-left:450px;
padding:9px 35px;
border-radius:20px;
}
</style>
</head>
<body id="body-color">

</body>
</html> 