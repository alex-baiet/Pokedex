<!DOCTYPE html>

<?php
  include("php/cookie_life.inc.php");
  include("php/connexion.inc.php");

  function createTableUser(string $user) {
    global $pdo;
    
    echo $pdo->exec("
      CREATE TABLE ".$user."_pokémon (
        Id_Pokémon integer PRIMARY KEY NOT NULL DEFAULT '0',
        nom varchar(50) DEFAULT NULL,
        poids decimal(15,2) DEFAULT NULL,
        taille decimal(15,2) DEFAULT NULL
      );");

    echo $pdo->exec("
      CREATE TABLE ".$user."_statistiques (
        Id_Statistiques integer PRIMARY KEY NOT NULL,
        hp integer DEFAULT NULL,
        def integer DEFAULT NULL,
        atq integer DEFAULT NULL,
        spe_atq integer DEFAULT NULL,
        spe_def integer DEFAULT NULL,
        vit integer DEFAULT NULL
      );");

    echo $pdo->exec("
      CREATE TABLE ".$user."_est_pourvu (
        Id_Pokémon integer NOT NULL DEFAULT '0',
        Id_Statistiques integer NOT NULL DEFAULT '0'
      );");
    
    echo $pdo->exec("
      CREATE TABLE ".$user."_possède (
        Id_Pokémon integer NOT NULL DEFAULT '0',
        Id_Type integer NOT NULL DEFAULT '0'
      );");

    echo $pdo->exec("
      CREATE TABLE ".$user."_est_doté (
        Id_Pokémon integer NOT NULL DEFAULT '0',
        Id_Talents integer NOT NULL DEFAULT '0',
        est_caché integer NOT NULL
      );");
  }

  function addUser() {
    global $pdo;

    // Vérification que le formulaire a bien été entré.
    if (!empty($_POST["new_user"]) 
      && !empty($_POST["new_password"]) 
      && !empty($_POST["confirm_password"])) 
    {
      $user = $_POST["new_user"]; // La variable $user existe déjà par défaut.
      $pass = md5($_POST["new_password"]);
      $confirm = md5($_POST["confirm_password"]);

      // Vérification du nouveau code.
      if ($pass != $confirm) {
        return "Les deux mot de passe entrés ne sont pas identiques.";
      }

      // (Tentative d')enregistrement du nouveau compte. 
      $result = $pdo->exec("
        INSERT INTO users VALUES 
        ('".$user."', '".$pass."')
        ;");

      // Vérification que le compte a bien été créé.
      if ($result !== 1) {
        return "L'utilisateur ".$user." existe déjà !<br>Utilisez un autre nom.";
      }

      // APRES CE STADE, TOUT EST VERIFIE
      // Création des tables
      createTableUser($user);

      // Connection au compte et retour à la page d'acceuil.
      setcookie("user", $user, time()+COOKIE_LIFE);
      header("location:index.php");
    }
  }


  function printError() {
    global $error;
    if (!is_null($error)) {
      echo '<div class="error">'.$error.'</div>';
    }
  }

  $error = addUser();
?>

<html>
<head>
  <meta charset="utf-8">
  <title>Créer un compte</title>
  <link rel="stylesheet" type="text/css" href="style.css">
</head>

<body>
  <?php include("php/header.inc.php")?>

  <h2>Crée un compte</h2>
  <?php printError(); ?>
  <form action="create_account.php" method="post">
    <label for="new_user">Nom d'utilisateur : </label>
    <input type="text" name="new_user" id="new_user"><br>

    <label for="new_password">Mot de passe : </label>
    <input type="password" name="new_password" id="new_password"><br>
    <label for="confirm_password">Confirmez le mot de passe : </label>
    <input type="password" name="confirm_password" id="confirm_password"><br>

    <button type="submit">Créer un compte</button>
  </form>
  
  <p>
    Vous avez déjà un compte ? <br>
    <a href="connect_account.php">Se connecter</a>
  </p>

</body>
</html>