<?php

$user_bdd = "abaiet";
$pass_bdd = "sx5"; // OUBLIE CE CODE IMMEDIATEMENT C'EST UN ORDRE
try {
  $pdo = new PDO('pgsql:host=sqletud.u-pem.fr;dbname=abaiet_db', $user_bdd, $pass_bdd);
  $pdo->exec("SET search_path TO project;");
}
catch (PDOException $e) {
  echo "ERREUR : La connexion a échouée";
}

?>

