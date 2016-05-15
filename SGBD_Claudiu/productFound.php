<?php
	session_start();
	$_POST=array_merge($_POST,$_SESSION);
	echo "<h1>Actualizarea produsului cu codul $_POST[barcode]:</h1>";
	 $connect = oci_pconnect("STUDENT", "STUDENT", "//localhost/XE:pooled");
	$command=oci_parse($connect,'begin select product_name,quantity,price,image,category_id into :product_name,:quantity,:price,:image,:category_id from product where barcode=:barcode; end;');
	oci_bind_by_name($command,':barcode',$_POST['barcode']);
	oci_bind_by_name($command,':product_name',$product_name,2000);
	oci_bind_by_name($command,':quantity',$quantity,2000);
	oci_bind_by_name($command,':price',$price,2000);
	oci_bind_by_name($command,':image',$image,2000);
	oci_bind_by_name($command,':category_id',$category_id,2000);
	oci_execute($command);
	
	if(isset($_POST['product_name']) && isset ($_POST['quantity']) && isset($_POST['price']) && isset($_POST['image']) && isset($_POST['category_id'])){
	$commandUpdate=oci_parse($connect,'begin updateProdus(:barcode, :product_name, :quantity, :price, :image, :category_id); end;');
	oci_bind_by_name($commandUpdate,':barcode',$_POST['barcode']);
	oci_bind_by_name($commandUpdate,':product_name',$_POST['product_name']);
	oci_bind_by_name($commandUpdate,':quantity',$_POST['quantity']);
	oci_bind_by_name($commandUpdate,':price',$_POST['price']);
	oci_bind_by_name($commandUpdate,':image',$_POST['image']);
	oci_bind_by_name($commandUpdate,':category_id',$_POST['category_id']);
	oci_execute($commandUpdate);
	$e = oci_error($commandUpdate);
	if($e){
			print $e['message'];
		}
		else{
			echo "<h2>Produs actualizat cu succes!</h2>";
			$product_name=$_POST['product_name'];
			$quantity=$_POST['quantity'];
			$price=$_POST['price'];
			$image=$_POST['image'];
			$category_id=$_POST['category_id'];
		}
  }
?>
<form method="POST">
 <pre>
Product name:<input type="text" name="product_name" value="<?php echo $product_name; ?>"<br>
    Quantity:<input type="text" name="quantity" value="<?php echo $quantity; ?>"><br>
       Price:<input type="text" name="price" value="<?php echo $price; ?>"><br>
       Image:<input type="text" name="image" value="<?php echo $image; ?>"><br>
 Category Id:<input type="text" name="category_id" value="<?php echo $category_id; ?>"><br>
 <input type="submit" value="Update">
  </pre>
</form>
