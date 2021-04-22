<!DOCTYPE html>

<?php
  include("php/connexion.inc.php");
  $id = $_GET["id_pokemon"];

  function pokemonExist() {
    global $pdo;
    global $id;

    $result = $pdo->query("
      SELECT * 
      FROM pokémon
      WHERE id_pokémon=".$id."
      ;");

    return ($result ? true : false);
  }
  
  pokemonExist();
  // Vérification que l'on a un pokémon a traiter
  if (empty($_GET["id_pokemon"]) || !pokemonExist()) {
    header("location:pokemon_list.php");
  }

  // Ecrit les informations de base du pokémon 
  function printInfoBasic() {
    global $pdo;
    global $id;

    $result = $pdo->query("
      SELECT * 
      FROM pokémon
      WHERE id_pokémon=".$id."
      ;");
    $pokemon = $result->fetch();
    $result->closeCursor();

    echo '
      <table class="pokemon_infos">
        <tr>
          <th>Nom</th>
          <th>'.$pokemon["nom"].'</th>
        </tr>
        <tr>
          <td>Numéro de pokédex</td>
          <td>'.$pokemon["id_pokémon"].'</td>
        </tr>
        <tr>
          <td>Poids</td>
          <td>'.($pokemon["poids"] / 10).' kg</td>
        </tr>
        <tr>
          <td>Taille</td>
          <td>'.($pokemon["taille"] / 10).' m</td>
        </tr>
      </table>
    ';
  }

  function printStats() {
    global $pdo;
    global $id;

    $result = $pdo->query("
      SELECT * 
      FROM est_pourvu AS has,
           statistiques AS stat
      WHERE stat.id_statistiques = has.id_statistiques
        AND has.id_pokémon = ".$id."
      ;");
    $stats = $result->fetch();
    $result->closeCursor();

    echo '
      <table class="pokemon_stats">
        <tr>
          <td>Vie</td>
          <td>'.$stats["hp"].'</td>
        </tr>
        <tr>
          <td>Attaque</td>
          <td>'.$stats["atq"].'</td>
        </tr>
        <tr>
          <td>Défense</td>
          <td>'.$stats["def"].'</td>
        </tr>
        <tr>
          <td>Attaque spécial</td>
          <td>'.$stats["spe_atq"].'</td>
        </tr>
        <tr>
          <td>Défense spécial</td>
          <td>'.$stats["spe_def"].'</td>
        </tr>
        <tr>
          <td>Vitesse</td>
          <td>'.$stats["vit"].'</td>
        </tr>
      </table>
      ';
  }

  function printTypes() {
    global $pdo;
    global $id;

    $result = $pdo->query("
      SELECT type.nom
      FROM type,
           possède AS possess
      WHERE type.id_type = possess.id_type
        AND possess.id_pokémon = ".$id."
      ;");
    
    echo '<table class="pokemon_types">';
    $count = 0;
    while($type = $result->fetch()) {
      $count++;
      echo '
        <tr>
          <td>Type '.$count.'</td>
          <td>'.$type["nom"].'</td>
        </tr>';
    }
    echo '</table>';

    $result->closeCursor();
  }

  function printGeneration() {
    global $pdo;
    global $id;

    $result = $pdo->query("
      SELECT *
      FROM génération AS gen,
           apparait AS spawn
      WHERE gen.id_génération = spawn.id_génération
        AND spawn.id_pokémon = ".$id."
      ;");
    
    $gen = $result->fetch();

    echo '
      <table class="pokemon_stats">
        <tr>
          <td>Génération d\'apparition</td>
          <td>'.$gen["nom"].'</td>
        </tr>
        <tr>
          <td>Date première apparition</td>
          <td>'.$gen["date_sortie"].'</td>
        </tr>
      </table>
      ';

    $result->closeCursor();
  }

  function printTalents() {
    global $pdo;
    global $id;

    $result = $pdo->query("
      SELECT talents.nom,
             has.est_caché
      FROM est_doté AS has,
           talents
      WHERE has.id_talents = talents.id_talents
        AND has.id_pokémon = ".$id."
      ;");
    
    echo '
      <table class="pokemon_talents">
        <tr>
          <th>Talent</th>
          <th>Caché</th>
        </tr>';
    if ($result) {
      while($talent = $result->fetch()) {
        echo '
          <tr>
            <td>'.$talent["nom"].'</td>
            <td>'.($talent["est_caché"] ? "oui" : "non").'</td>
          </tr>';
      }
      $result->closeCursor();
    }
    echo '</table>';

  }

  //capacité, faiblesse pokemon
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
    include("php/header.inc.php");
  ?>

  <content>

    <?php 
      printInfoBasic(); 
      printStats();
      printTypes();
      printGeneration();
      printTalents();
    ?>

  </content>
</body>
</html>