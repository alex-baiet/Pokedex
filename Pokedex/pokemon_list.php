<!DOCTYPE html>

<?php
  include("php/connexion.inc.php");
  $prefix = "";
  $search = "";
  $list = "";

  // La requete doit donner l'"id" du pokemon et son "name".
  function printList(string $req, string $title) {
    global $pdo;
    global $prefix;

    $result = $pdo->query($req);
    if ($result) {
      
      $pokemons = array();
      while ($poke = $result->fetch()) {
        $pokemons[] = $poke;
      }

      if (count($pokemons) != 0) {
        echo '<h3>'.$title.'</h3>';

        foreach ($pokemons as $poke) {
          echo '
            <form method="post" action="pokemon_infos.php" class="list_unit">
              <div class="display_none">
                <input type="number" name="id_pokemon" value="'.$poke["id"].'">
              </div>
              <button type=submit>
                '.$poke["id"].' - '.$poke["name"].'
              </button>
              <input name="prefix" value="'.$prefix.'" hidden>
            </form>';
        }
      }
    } else {
      echo "<pre>ERREUR lors de l'exécution de :\n$req.</pre>";
    }

  }


  // Affiche la liste des pokemons dans la BDD.
  function listPokemon() {
    global $pdo;
    global $prefix;

    $order = isset($_GET["order"]) ? $_GET["order"] : "default";

    switch ($order) {
      // Tri par numéro
      case 'default':
        $res = $pdo->query("
          SELECT id_génération AS id, nom AS name 
          FROM génération
          ORDER BY génération.id_génération;");

        while ($line = $res->fetch()) {
          $req = "
            SELECT pokémon.id_pokémon AS id,
                   pokémon.nom AS name
            FROM ".$prefix."pokémon,
                 ".$prefix."apparait
            WHERE pokémon.id_pokémon = apparait.id_pokémon
              AND pokémon.id_pokémon < 10000
              AND apparait.id_génération = ".$line["id"]."
            ORDER BY pokémon.id_pokémon;
          ;";

          printList($req, $line["name"]);
        }
        break;
      
      // Tri par ordre alphabétique
      case 'alphabet':
        $alphabet = "abcdefghijklmnopqrstuvwxyz";
        for ($i=0; $i<26; $i++) {
          $req = "
            SELECT pokémon.id_pokémon AS id,
                   pokémon.nom AS name
            FROM ".$prefix."pokémon
            WHERE pokémon.id_pokémon < 10000
              AND pokémon.nom LIKE '".$alphabet[$i]."%'
            ORDER BY pokémon.nom;
          ;";

          printList($req, strtoupper($alphabet[$i]));
        }
        break;
      
      // Tri par type
      case 'type':
        $res = $pdo->query("
          SELECT id_type AS id, nom AS name 
          FROM type
          ORDER BY type.nom;");

        while ($line = $res->fetch()) {
          $req = "
            SELECT pokémon.id_pokémon AS id,
                   pokémon.nom AS name
            FROM ".$prefix."pokémon,
                 ".$prefix."possède
            WHERE pokémon.id_pokémon = possède.id_pokémon
              AND pokémon.id_pokémon < 10000
              AND possède.id_type = ".$line["id"]."
            ORDER BY pokémon.id_pokémon;
          ;";

          printList($req, $line["name"]);
        }
        break;
      
      // Tri par poids
      case 'weight':
        $weight_array = array(array(0, 0.001));

        for ($i=-3; $i<12; $i++) {
          $weight_array[] = array(
            pow(10, $i), 
            pow(10, $i+1));
        }

        foreach ($weight_array as $weight) {
          $req = "
            SELECT pokémon.id_pokémon AS id,
                   pokémon.nom AS name
            FROM ".$prefix."pokémon
            WHERE pokémon.id_pokémon < 10000
              AND pokémon.poids BETWEEN ".($weight[0]*10)." AND ".($weight[1]*10)."
            ORDER BY pokémon.poids;
          ;";

          printList($req, $weight[0]." - ".$weight[1]);
        }
        break;
      
      // Tri par taille
      case 'height':
        $height_array = array(array(0, 0.001));

        for ($i=-3; $i<12; $i++) {
          $height_array[] = array(
            pow(10, $i), 
            pow(10, $i+1));
        }

        foreach ($height_array as $height) {
          $req = "
            SELECT pokémon.id_pokémon AS id,
                   pokémon.nom AS name
            FROM ".$prefix."pokémon
            WHERE pokémon.id_pokémon < 10000
              AND pokémon.poids BETWEEN ".($height[0]*10)." AND ".($height[1]*10)."
            ORDER BY pokémon.poids;
          ;";

          printList($req, $height[0]." - ".$height[1]);
        }
        break;
      
      default:
        echo '<p class="error">Une erreur est survenu lors de la recherche.</p>';
        $order = 'default';
        break;  
    }
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
    <h2>Option de tri</h2>
    <!-- par generation, par alphabet, par type, poids, taille -->
    <form method="get" action="pokemon_list.php">
      <label for="order">Trier par :</label>
      <select name="order" id="order" size="1">
        <option value="default">Numéro du pokédex</option>
        <option value="alphabet">Ordre alphabetique</option>
        <option value="type">Type</option>
        <option value="weight">Poids</option>
        <option value="height">Taille</option>
      </select>
      <button type="submit">Rechercher</button>
    </form>

    <!-- Zone de recherche (?) -->

    <h2>Liste des pokémons</h2>
    <!-- Ici raccourci vers les différentes parties (non intégrées) -->

    <!-- List des pokémons -->
    <?php listPokemon(); ?>
  </content>
  
</body>
</html>