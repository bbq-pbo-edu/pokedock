<?php

echo 'show' . '<br>';

$record = findById('pokemon', $id);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokédex - <?= $record['name'] ?> </title>
</head>
<body>
    <a href="http://www.pokemon.patrick.web.bbq/pokemon">pokemon</a>
    <a href="http://www.pokemon.patrick.web.bbq/pokemon/add">pokemon/add</a>
    <a href="http://www.pokemon.patrick.web.bbq/pokemon/read">pokemon/read</a>

    <h1><?= $record['name'] ?></h1>
    <h2>Pokédex-Nr.: <?= $record['pokedex_nr'] ?></h2>
    <p><?= $record['description'] ?></p>
    <p>Typ 1: <?= $record['type_1'] ?></p>
    <p>Typ 2: <?= $record['type_2'] ?></p>
    <p>Gefangen: <?= $record['caught'] ?></p>
    <a href="/pokemon/edit/<?= $record['id'] ?>"><button>Edit</button></a>
    <a href="/pokemon/delete/<?= $record['id'] ?>"><button>Delete</button></a>
</body>
</html>
