<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $pokemonData = $_POST;
    create('pokemon', $pokemonData);
    $newId = findLatestId('pokemon');

    header('Location: /pokemon/show/' . $newId);
    exit();
}

header('Location: /pokemon/read/');
exit();