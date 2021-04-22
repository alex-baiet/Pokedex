<!DOCTYPE html>

<?php
  include("php/pdo_functions.inc.php");

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
      <label for="weight" required>Poids</label>
      <input type="text" name="weight" id="weight"><br>
      <label for="height" required>Taille</label>
      <input type="text" name="height" id="height"><br>

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
        for ($i=0; $i < 10; $i++) { 
          echo createSelectFromSQL($req, "talent".$i, 1, false, 0).'<br>';
        }
      ?>
      <br>
      <button type="submit">Créer</button>

    </form>

  </content>
  
</body>
</html>