<!DOCTYPE html>

<?php
  include("php/connexion.inc.php");
  $id = $_POST["id_pokemon"];
  $prefix = $_POST["prefix"];

  function pokemonExist() {
    global $pdo;
    global $prefix;
    global $id;

    $result = $pdo->query("
      SELECT * 
      FROM ".$prefix."pokémon
      WHERE id_pokémon=".$id."
      ;");

    return ($result ? true : false);
  }
  
  pokemonExist();
  // Vérification que l'on a un pokémon a traiter
  if (empty($_POST["id_pokemon"]) || !pokemonExist()) {
    header("location:pokemon_list.php");
  }

  // Ecrit les informations de base du pokémon 
  function printInfoBasic() {
    global $pdo;
    global $prefix;
    global $id;

    $result = $pdo->query("
      SELECT * 
      FROM ".$prefix."pokémon
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
  function printImage() {
    global $id;
    echo '<img class ="pokemonIcon" src="img/pokemon/icons/'.$id.'.png">';
  }

  function printStats() {
    global $pdo;
    global $prefix;
    global $id;

    $result = $pdo->query("
      SELECT * 
      FROM ".$prefix."est_pourvu AS has,
           ".$prefix."statistiques AS stat
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

  function getTypes() {
    global $pdo;
    global $prefix;
    global $id;

    return $pdo->query("
      SELECT *
      FROM type,
           ".$prefix."possède AS possess
      WHERE type.id_type = possess.id_type
        AND possess.id_pokémon = ".$id."
      ;");
    
  }

  function printTypes() {
    global $pdo;
    global $prefix;
    global $id;

    $result = getTypes();
    
    echo '<table class="pokemon_types">';
    $count = 0;
    while($type = $result->fetch()) {
      $count++;
      echo '
        <tr>
          <td>Type '.$count.'</td>
          <td><img src="img/types/'.$type["nom"].'.png"></td>
        </tr>';
    }
    echo '</table>';

    $result->closeCursor();
  }

  function printGeneration() {
    global $pdo;
    global $prefix;
    global $id;

    $gen;
    if (!$prefix) {
      $result = $pdo->query("
        SELECT *
        FROM génération AS gen,
             apparait AS spawn
        WHERE gen.id_génération = spawn.id_génération
          AND spawn.id_pokémon = ".$id."
        ;");
      
      $gen = $result->fetch();
      $result->closeCursor();
    } else {
      $gen = array(
        "nom" => "personnal",
        "date_sortie" => "",
      );
    }

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

  }

  function printTalents() {
    global $pdo;
    global $prefix;
    global $id;

    $result = $pdo->query("
      SELECT talents.nom,
             has.est_caché
      FROM ".$prefix."est_doté AS has,
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
  function getEvolution(){
    global $pdo;
    global $id;
    global $prefix;
    return $pdo->query("
      SELECT id_pokémon
      FROM evolue
      WHERE id_pokémon_1=".$id."
      ;
      ");
  }
  function getUnderEvolution(){
    global $pdo;
    global $id;
    global $prefix;
    return $pdo->query("
      SELECT id_pokémon_1
      FROM evolue
      WHERE id_pokémon=".$id."
      ;
      ");
  }
  function displayArrowEvolution(){
    global $pdo;
    global $id;
    global $prefix;
    $result = getUnderEvolution();
    $poke=$result->fetch()[0];
    if ($result == true and $poke !=0) {
      echo '
    <form method="post" name="poke_info1" action="pokemon_infos.php">
    <div class="display_none">
    <input type="number" name="id_pokemon" value="'.$poke.'">
    <input type="number" name="prefix" value="'.$prefix.'">
    </div>
    <div class="leftArrow">
    <a href="javascript:javascript: submitform1();"><img src="img/left_arrow.png"></a>
    <img src="img/pokemon/icons/'.$poke.'.png">
    </div></form>';
    }
    $result2 = getEvolution();
    $poke2=$result2->fetch()[0];
    if ($result2 == true and $poke2!=0) {
      echo '
    <form method="post" name="poke_info2" action="pokemon_infos.php">
    <div class="display_none">
    <input type="number" name="id_pokemon" value="'.$poke2.'">
    <input type="number" name="prefix" value="'.$prefix.'">
    </div>
    <div class="rightArrow">
    <a href="javascript: submitform2();"><img src="img/right_arrow.png"></a>
    <img src="img/pokemon/icons/'.$poke2.'.png">
    </div></form>';
    }
  }
  function getTypeByEfficiency(int $id_type, int $efficiency=100) {
    global $pdo;
    global $prefix;

    return $pdo->query("
      SELECT type.id_type,
             type.nom
      FROM efficacité AS efficiency,
           type
      WHERE efficiency.id_type = type.id_type
        AND efficiency.id_type_1 = ".$id_type."
        AND efficiency.facteur_d_efficacité = ".$efficiency."
      ;");
  }

  function printWeakness() {
    global $pdo;
    global $prefix;
    global $id;

    // Initialisation des variables
    $result = getTypes();
    $type_array = array();
    while ($type = $result->fetch()) {
      $type_array[] = $type["id_type"];
    }
    $result->closeCursor();

    $effic_array = array(
      "Sans effet" => 0,
      "Résistance" => 50,
      "Faiblesse" => 200,
      "test" => 999
    );
    
    $type_result;
    
    echo '<table class="pokemon_resistances">';
    foreach ($effic_array as $eff_name => $eff_val) { // Pour chaque efficacité possible
      // Récupération des types de cette efficacité
      $type_result = array();
      foreach ($type_array as $id_type) {
        $result = getTypeByEfficiency($id_type, $eff_val);

        while ($t = $result->fetch()) {
          $type_result[$t["id_type"]] = $t["nom"];
        }
        $result->closeCursor();
      }

      // Affichage des résultats
      if (count($type_result) != 0) {
        echo '<tr><th>'.$eff_name.'</th><td>';
        $first = true;
        foreach($type_result as $t) {
          if ($first) { $first = false; } 
          else { echo ', '; }
          echo '<img src="img/types/'.$t.'.png">';
        }
        $result->closeCursor();
        echo '</td></tr>';
      }
    }
    echo '</table>';
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
	<script type="text/javascript">
		function submitform1() {
			document.poke_info1.submit();
		}
		function submitform2() {
			document.poke_info2.submit();
		}
	</script>
  <?php
    include("php/header.inc.php");
  ?>

  <content class="info">
    <?php 
      printImage();
      printInfoBasic(); 
      printTypes();
      printStats();
      printTalents();
      printGeneration();
      printWeakness();
      displayArrowEvolution();
    ?>
  </content>
</body>
</html>