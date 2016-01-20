<?php print_r ($_POST);
$datiUtente = $_POST;
print_r ($datiUtente);
?>


<!DOCTYPE html>
<html>
  <head>

    <ul class="list-group">
      <li class="list-group-item"><?=$datiUtente['nome'] ?></li>
      <li class="list-group-item"><?=$datiUtente['cognome'] ?></li>
      <li class="list-group-item"><?=$datiUtente['email'] ?></li>
      <li class="list-group-item"><?=$datiUtente['indirizzo'] ?></li>
      <li class="list-group-item"><?=$datiUtente['citta'] ?></li>
      <li class="list-group-item"><?=$datiUtente['cap'] ?></li>
      <li class="list-group-item"><?=$datiUtente['provincia'] ?></li>
      <li class="list-group-item"><?=$datiUtente['note'] ?></li>
      </ul>
      <ul class="list-group">
        <li class="list-group-item"><?=$datiUtente['nome'] ?></li>
      </ul>

  </head
</html
