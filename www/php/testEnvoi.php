<?php
include_once "config.php";

// if (isset($_POST['coucou'])) {
	// on affiche nos résultats
	$coucou = $_POST['coucou'];

  echo json_encode($coucou);
// }

 ?>
