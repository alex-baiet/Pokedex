<!-- Ce script supprime les données de tous les utilisateurs, ainsi que leurs comptes. -->

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
      // A mettre dans une array pour plus de place
      $t1 = $user["name"]."_pokémon";
      $t2 = $user["name"]."_statistiques";
      $t3 = $user["name"]."_est_pourvu";
      $t4 = $user["name"]."_est_doté";
      $t5 = $user["name"]."_possède";
      $t6 = $user["name"]."_apparait";

      echo "DROP de ".$t1." : ".dropTable($t1)."\n";
      echo "DROP de ".$t2." : ".dropTable($t2)."\n";
      echo "DROP de ".$t3." : ".dropTable($t3)."\n";
      echo "DROP de ".$t4." : ".dropTable($t4)."\n";
      echo "DROP de ".$t5." : ".dropTable($t5)."\n";
      echo "DROP de ".$t6." : ".dropTable($t6)."\n";
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
      <button name="confirm" value="1">
        Confirmer la suppression de tous les utilisateurs.
        CETTE ACTION EST IRREVERSIBLE !
      </button>
    </form>

    <pre>
      <?php 
        if (isset($_POST["confirm"]) && $_POST["confirm"] == 1) deleteAll(); 
        else echo "Rien ne s'est passé pour le moment. MAIS ATTENTION !";
      ?>
    </pre>
  </content>
  
</body>
</html>