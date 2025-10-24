<?php

echo 'read' . '<br>';

echo '<a href="http://www.pokemon.patrick.web.bbq/pokemon">pokemon</a>
<a href="http://www.pokemon.patrick.web.bbq/pokemon/add">pokemon/add</a>
<a href="http://www.pokemon.patrick.web.bbq/pokemon/read">pokemon/read</a>

<link rel="stylesheet" href="../assets/css/read.css">';

echo createTable(findAll('pokemon'), true);