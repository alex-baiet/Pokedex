<!DOCTYPE html>

<?php
  include("php/pdo_functions.inc.php");
  $user;
  if (!empty($_COOKIE["user"])) { $user = $_COOKIE["user"]; } 
  else { header("location:index.php"); } 

  function addNewPokemon() {
    global $pdo;
    global $user;

    // INSERT pokémon
    $result = $pdo->query("
      SELECT MAX(id_pokémon)
      FROM ".$user."_pokémon
      ;");
    
    $new_poke = $result->fetch();
    if(count($new_poke != 0)) {
      $new_poke = $new_poke[0]+1 ;
    } else {
      $new_poke = 0;
    }

    if (!$pdo->exec("
      INSERT INTO ".$user."_pokémon VALUES
      (".$new_poke.", '".$_POST["name"]."', '".($_POST["weight"]*10)."', '".($_POST["height"]*10)."')
      ;")) {
      echo "Une erreur est survenu lors de l'insertion des données du pokémon.";
    }

    // INSERT statistiques
    $result = $pdo->query("
      SELECT MAX(id_statistiques)
      FROM ".$user."_statistiques
      ;");
    
    $new_stat = $result->fetch();
    if(count($new_stat != 0)) {
      $new_stat = $new_stat[0]+1 ;
    } else {
      $new_stat = 0;
    }

    if (!$pdo->exec("
      INSERT INTO ".$user."_statistiques VALUES (
        ".$new_stat.", 
        ".$_POST["hp"].",
        ".$_POST["atq"].",
        ".$_POST["def"].",
        ".$_POST["spe_atq"].",
        ".$_POST["spe_def"].",
        ".$_POST["vit"]."
      );")) {
      echo "Une erreur est survenu lors de l'insertion des données des statistiques (1).";
    }

    // INSERT est_pourvu (de stats)
    if (!$pdo->exec("
      INSERT INTO ".$user."_est_pourvu VALUES
      (".$new_poke.", ".$new_stat.")
      ;")) {
      echo "Une erreur est survenu lors de l'insertion des données des statistiques (2).";
    }

    // INSERT possède (des types)
    $types = array();
    for ($i=1; $i <= 2; $i++) { 
      $types[$_POST["type".$i]] = $_POST["type".$i];
    }

    foreach ($types as $t) {
      if ($t != 0) { // si le type n'est pas inconnu
        if (!$pdo->exec("
          INSERT INTO ".$user."_possède VALUES
          (".$new_poke.", ".$t.")
          ;")) {
          echo "Une erreur est survenu lors de l'insertion des données des types.";
        }
      }
    }

    // INSERT est_doté (de talents)
    $talents = array();
    for ($i=1; $i <= 4; $i++) { 
      $talents[$_POST["talent".$i]] = array(
        $_POST["talent".$i],
        (isset($_POST["hidden".$i]) ? 1 : 0)
      );
    }

    foreach ($talents as $t) {
      if ($t[0] != 0) { // si le talent n'est pas inconnu
        if (!$pdo->exec("
          INSERT INTO ".$user."_est_doté VALUES
          (".$new_poke.", ".$t[0].", ".$t[1].")
          ;")) {
          echo "Une erreur est survenu lors de l'insertion des données des talents.";
        }
      }
    }

  }


  if (isset($_POST["name"])) {
    addNewPokemon();
  }
  // penser a l'id du pokemon auto, verifier que les 2 types sont differents
?>

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
    <h2>Créer un nouveau pokémon</h2>

    <form method="post" action="pokemon_create.php">
      
      <h3>Informations principals</h3>
      <label for="name" required>Nom</label>
      <input type="text" name="name" id="name"><br>
      <label for="weight" required>Poids (kg)</label>
      <input type="text" name="weight" id="weight" value="0"><br>
      <label for="height" required>Taille (m)</label>
      <input type="text" name="height" id="height" value="0"><br>

      <h3>Statistiques</h3>
      <label for="hp">Vie</label>
      <input type="number" name="hp" id="hp" value="60" required><br>
      <label for="atq">Attaque</label>
      <input type="number" name="atq" id="atq" value="60" required><br>
      <label for="def">Défense</label>
      <input type="number" name="def" id="def" value="60" required><br>
      <label for="spe_atq">Attaque spécial</label>
      <input type="number" name="spe_atq" id="spe_atq" value="60" required><br>
      <label for="spe_def">Défense spécial</label>
      <input type="number" name="spe_def" id="spe_def" value="60" required><br>
      <label for="vit">Vitesse</label>
      <input type="number" name="vit" id="vit" value="60" required><br>

      <h3>Types</h3>
      <?php
        $req = "SELECT id_type, nom FROM type ORDER BY nom;";
        echo '<label for="type1">Type 1 : </label>';
        echo createSelectFromSQL($req, "type1", 1, true, 1).'<br>';
        echo '<label for="type2">Type 2 : </label>';
        echo createSelectFromSQL($req, "type2", 1, true, 0).'<br>';
      ?>

      <h3>Talents</h3>
      <?php
        $req = "SELECT id_talents, nom FROM talents ORDER BY nom;";
        for ($i=1; $i <= 4; $i++) { 
          echo createSelectFromSQL($req, "talent".$i, 1, false, 0);
          //echo 'hidden'.$i;
          echo '<input type="checkbox" name="hidden'.$i.'" value="1"><br>';
        }
      ?>
      <br>
      <button type="submit">Créer</button>

    </form>

  </content>
  
</body>
</html>