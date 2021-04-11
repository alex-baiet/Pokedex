<?php

$user =  "abaiet";
$pass =  "sx5"; // OUBLIE CE CODE IMMEDIATEMENT C'EST UN ORDRE
try {
  $pdo = new PDO('pgsql:host=sqletud.u-pem.fr;dbname=abaiet_db',$user,$pass);
  $pdo->exec("SET search_path TO project;");
}
catch (PDOException $e) {
  echo "ERREUR : La connexion a échouée";
}

?>

