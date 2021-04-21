<?php

if (isset($_COOKIE["user"])) { // Cas utilisateur connecté
  echo '
    <header>
      <div class="header_list">
        <a class="header_elem_side" href="index.php"><span>Accueil</span></a>
        <a class="header_elem" href="pokemon_list.php"><span>Pokémons</span></a>
        <a class="header_elem" href="capacity_list.php"><span>Capacités</span></a>
        <div class="header_elem_side">
          <span>'.$_COOKIE["user"].'</span>
          <div class="header_sublist">
            <a href="disconnect_account.php">Se déconnecter</a><br>
            <a href="pokemon_user_list.php">Vos pokémons</a><br>
            <a href="capacity_user_list.php">Vos capacités</a>
          </div>
        </div>
      </div>
      <hr>
    </header>
  ';
} else { // Cas utilisateur inconnu
  echo '
    <header>
      <div class="header_list">
        <a class="header_elem_side" href="index.php"><span>Accueil</span></a>
        <a class="header_elem" href="pokemon_list.php"><span>Pokémons</span></a>
        <a class="header_elem" href="capacity_list.php"><span>Capacités</span></a>
        <a class="header_elem_side" href="connect_account.php"><span>Connexion</span></a>
      </div>
      <hr>
    </header>
  ';
}

?>