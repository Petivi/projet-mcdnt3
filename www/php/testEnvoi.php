<?php
include_once "config.php";

// if (isset($_POST['coucou'])) {
    // on affiche nos résultats
    $coucou = file_get_contents("php://input");
    $request = json_decode($coucou);
    $result = $request->coucou;

  echo json_encode($result);
// }

 ?>
