<!DOCTYPE html>

<?php
  include("php/connexion.inc.php");
  $prefix = "";

  // Affiche la liste des pokemons dans la BDD.
  function listPokemon() {
    global $pdo;
    global $prefix;

    // Récupération des données
    $result = $pdo->query("
      SELECT id_pokémon AS id, 
             nom AS name
      FROM ".$prefix."pokémon 
      ;");

    // Affichage des pokémons
    echo '<div class="list_search">';
    while ($pokemon = $result->fetch()) {
      echo '
        <form method="post" action="pokemon_infos.php" class="list_unit">
          <div class="display_none">
            <input type="number" name="id_pokemon" value="'.$pokemon["id"].'">
          </div>
          <button type=submit>
            '.$pokemon["id"].' - '.$pokemon["name"].'
          </button>
          <input name="prefix" value="'.$prefix.'" hidden>
        </form>';
    }
    echo "</div>";
  }

  if (isset($_POST["use_user_data"]) && isset($_COOKIE["user"])) {
    $prefix = $_COOKIE["user"]."_";
  }
?>

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
    <h2>Liste des pokémons</h2>
    <!-- Ici raccourci vers les différentes parties -->

    <!-- List des pokémons -->
    <?php listPokemon(); ?>
  </content>
  
</body>
</html>