<?php

echo'
  <header>
    <div class="header_list">
      <a class="header_elem_side" href="index.php"><span>Accueil</span></a>
      <a class="header_elem" href="pokemon_list.php"><span>Pokémons</span></a>
      <!--<a class="header_elem" href="capacity_list.php"><span>Capacités</span></a>-->
';

if (isset($_COOKIE["user"])) { // Cas utilisateur connecté
  echo '
    <div class="header_elem_side">
      <span>'.$_COOKIE["user"].'</span>
      <div class="header_sublist create">
        <a href="disconnect_account.php">Se déconnecter</a><br>
        <a href="pokemon_create.php">Créer un pokémon</a><br>
        <a>
          Vos pokémons
          <form method="post" action="pokemon_list.php">
            <button type="submit"></button>
            <input name="use_user_data" value="1" hidden>
          </form>
        </a>
      </div>
    </div>
  ';
} else { // Cas utilisateur inconnu
  echo '<a class="header_elem_side" href="connect_account.php"><span>Connexion</span></a>';
}

echo '
    </div>
    <hr>
  </header>
';

?>