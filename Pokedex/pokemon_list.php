<!DOCTYPE html>

<?php
  include("php/connexion.inc.php");

  // Affiche la liste des pokemons dans la BDD.
  function listPokemon() {
    global $pdo;

    // Récupération des données
    $result = $pdo->query("
      SELECT id_pokémon AS id, 
             nom AS name
      FROM pokémon 
      ;");

    // Affichage des pokémons
    echo '<div class="list_search">';
    while ($pokemon = $result->fetch()) {
      echo '
        <form method="get" action="pokemon_infos.php" class="list_unit">
          <div class="display_none">
            <input type="number" name="id_pokemon" value="'.$pokemon["id"].'">
          </div>
          <button type=submit>
            '.$pokemon["id"].' - '.$pokemon["name"].'
          </button>
        </form>';
    }
    echo "</div>";
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