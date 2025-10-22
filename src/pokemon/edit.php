<?php

$record = findById('pokemon', $id);
$caughtChecked = $record['caught'] == 0 ? '' : 'checked';

$typeRadioHTMLString = '';

for ($i = 1; $i <= 2; $i++) {
    $typeRadioHTMLString .= "<h3>Typ {$i}</h3>";
    foreach ($typeList as $type) {
        $typeRadioHTMLString .=
            "
        <label for={$type}_{$i}>{$type}
            <input type='radio' name='type_{$i}' id='{$type}_{$i}' value='{$type}'>
        </label>
        ";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Pokédex - <?= $record['name'] ?> - Edit</title>
</head>
<body>
    <a href="http://www.pokemon.patrick.web.bbq/pokemon">pokemon</a>
    <a href="http://www.pokemon.patrick.web.bbq/pokemon/add">pokemon/add</a>
    <a href="http://www.pokemon.patrick.web.bbq/pokemon/read">pokemon/read</a>

    <form method="POST" action="/pokemon/update/<?= $record['id'] ?>">
        <label for="name">Name: </label>
        <input type="text" id="name" name="name" value="<?= $record['name'] ?>"><br>
        <label for="pokedex_nr">Pokédex-Nr.: </label>
        <input type="number" id="pokedex_nr" name="pokedex_nr" value="<?= $record['pokedex_nr'] ?>"<br>
        <label for="description">Description: </label>
        <input type="text" id="description" name="description" value="<?= $record['description'] ?>"><br>

        <?= $typeRadioHTMLString ?>

        <br><br>
        <label for="caught">Gefangen:
            <input type="hidden" name="caught" value="0">
            <input type="checkbox" id="caught" name="caught" value="1" <?= $caughtChecked ?>><br>
        </label>
        <button type="submit">Speichern</button>
        <button formaction="/pokemon/show/<?= $record['id'] ?>">Abbrechen</button>
    </form>
</body>
</html>
