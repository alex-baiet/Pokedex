<?php
  if (!isset($pdo)) { include("connexion.inc.php"); }

  // la requete doit renvoyer un tableau a 2 colonnes.
  function createSelectFromSQL(string $req, string $name, int $size=1, bool $required=false, $defaultValue="") {
    global $pdo;

    // Requete
    $result = $pdo->query($req);

    // Ecriture <select> 
    $select = '<select 
      name="'.$name.'" 
      id="'.$name.'" 
      size="'.$size.'" 
      '.($required ? "required" : "").'>';
    while ($line = $result->fetch()) {
      $select .= '<option 
        value="'.$line[0].'" 
        '.($defaultValue == $line[0] ? "selected" : "").'>
          '.$line[1].'
        </option>';
    }
    $select .= '</select>';

    return $select;
  }
?>