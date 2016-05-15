<?php
	session_start();
	echo "<h1>Aveti voie sa executati procedurile/functiile : specifice unui User</h1>";
?>
<form action ="http://localhost/SGBD_Claudiu/produsulSaptamanii.php">
	<input type="submit" value="Vezi produsul saptamanii">
</form>
<form action ="http://localhost/SGBD_Claudiu/cotareProdusCategorii.php">
	<input type="submit" value="Vezi cele mai cotate produse pe categorii">
</form>
<form action ="http://localhost/SGBD_Claudiu/creeazaCampanie.php">
	<input type="submit" value="Creeaza o campanie!">
</form>