<!DOCTYPE html>
<?php
  include("php/connexion.inc.php");
  // Déconnexion de l'utilisateur actuel.
  if (!empty($_COOKIE["user"])) {
    setcookie("user", NULL, -1);
  }

  // Supprime la table
  function dropTable(string $table_name) {
    global $pdo;

    return $pdo->exec("DROP TABLE IF EXISTS ".$table_name." CASCADE;");
  }

  // Vide la table
  function truncateTable(string $table_name) {
    global $pdo;

    return $pdo->exec("TRUNCATE TABLE ".$table_name.";");
  }

  // retourne un PDO::STATEMENT contenant tous les utilisateurs. 
  function getUsers() {
    global $pdo;

    return $pdo->query("SELECT * FROM users;");
  }

  // Supprime tous les utilisateurs et leurs tables.
  function deleteAll() {
    // Suppression des tables de tous les utilisateurs
    $users = getUsers();

    while ($user = $users->fetch()) {
      $t1 = $user["name"]."-pokémon";
      $t2 = $user["name"]."-statistiques";
      $t3 = $user["name"]."-est_pourvu";

      echo "DROP de ".$t1." : ".dropTable($t1)."\n";
      echo "DROP de ".$t2." : ".dropTable($t2)."\n";
      echo "DROP de ".$t3." : ".dropTable($t3)."\n\n";
    }
    $users->closeCursor();
    
    // Suppression des utilisateurs
    echo "TRUNCATE de users : ".truncateTable("users")."\n";
  }
?>

<!-- PARTIE HTML -->
<html>
<head>
  <meta charset="utf-8">
  <title>Pokédex</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <?php
    include("php/header.inc.php")
  ?>

  <content>
    <form method="post" action="DELETE.php">
      <button name="confirm" value="1">Confirmer la suppression de tous les utilisateurs</button>
    </form>

    <pre>
      <?php 
        if (isset($_FORM["confirm"]) && $_FORM["confirm"] == 1) deleteAll(); 
        else echo "Rien ne s'est passé pour le moment. MAIS ATTENTION !";
      ?>
    </pre>
  </content>
  
</body>
</html>