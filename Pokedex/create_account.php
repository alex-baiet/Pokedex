<!DOCTYPE html>

<?php
  include("php/cookie_life.inc.php");

  function addUser() {

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
      include("php/connexion.inc.php");
      $result = $pdo->exec("
        INSERT INTO users VALUES 
        ('".$user."', '".$pass."')
        ;");

      // Vérification que le compte a bien été créé.
      if ($result !== 1) {
        return "L'utilisateur ".$user." existe déjà !<br>Utilisez un autre nom.";
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

  $error = addUser();
?>

<html>
<head>
  <meta charset="utf-8">
  <title>Connection</title>
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