<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Pokédex</title>
  <link rel="stylesheet" type="text/css" href="css/style.css">
</head>

<body>
  <pre>
    <?php
      include("php/connexion.inc.php");
      $result = $pdo->query("SELECT * FROM evolue;");

      while ($line = $result->fetch()) {
        print_r($line);
      }
      $result->closeCursor();
    ?>

    <?php
    ?>
  </pre>
</body>
</html>