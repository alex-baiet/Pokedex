<!DOCTYPE html>

<?php
  include("php/cookie_life.inc.php"); // importe la constante COOKIE_LIFE.

  function connectUser() {

    // Vérification que le formulaire a bien été entré.
    if (!empty($_POST["user"]) 
      && !empty($_POST["password"]) ) 
    {
      $user = $_POST["user"]; // La variable $user existe déjà par défaut.
      $pass = $_POST["password"];

      // Récupération du mot de passe de l'utilisateur
      include("php/connexion.inc.php");
      $result = $pdo->query("
        SELECT password 
        FROM users
        WHERE name = '".$user."'
        ;");

      // Vérification user existe TEST
      $true_pass = $result->fetch();
      if (!$true_pass) {
        return "L'utilisateur ".$user." n'existe pas.";
      }

      // Vérification du mdp.
      if ($pass !== $true_pass["password"]) {
        return "Les deux mot de passe entrés ne sont pas identiques.";
      }

      // Connection au compte et retour à la page d'acceuil.
      setcookie("user", $user, time()+COOKIE_LIFE);
      header("location:index.php");
    }
  }


  function printError() {
    global $error;
    if (!is_null($error)) {
      echo '<p class="error">'.$error.'</p>';
    }
  }

  $error = connectUser();
?>

<html>
<head>
  <meta charset="utf-8">
  <title>Connection</title>
  <link rel="stylesheet" type="text/css" href="css/main_style.css">
</head>

<body>
  <?php include("php/header.inc.php")?>

  <content>
    <h2>Connection</h2>
    <?php printError() ?>
    <form action="connect_account.php" method="post">
      <label for="user">Nom d'utilisateur : </label>
      <input type="text" name="user" id="user"><br>

      <label for="password">Mot de passe : </label>
      <input type="text" name="password" id="password"><br>

      <button type="submit">Se connecter</button>
    </form>

    <p>
      Pas de compte ? <br>
      vous pouvez en créer un ici :<br>
      <a href="create_account.php">Créer un compte</a>
    </p>
  </content>

</body>
</html>