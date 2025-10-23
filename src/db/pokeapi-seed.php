<?php

require_once '../../config/config.php';
require_once './database.php';

$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => 'https://pokeapi.co/api/v2/pokemon?limit=151',
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => 'GET',
));

$response = curl_exec($curl);

$responseArray = json_decode($response, true);
$pokemonList = $responseArray['results'];

$count = count($pokemonList);

$conn = createDBConnection(host: 'localhost');
$stmt = $conn->prepare("
INSERT INTO pokemon (pokedex_nr, name, caught, type_1, type_2, description) VALUES (:pokedex_nr, :name, :caught, :type_1, :type_2, :description)");

for ($i = 0; $i < $count; $i++) {
    $pokedex_nr = $i + 1;
    $name = $pokemonList[$i]['name'];
    $caught = 0;

    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://pokeapi.co/api/v2/pokemon/' . ($i + 1),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));
    $typeArray = json_decode(curl_exec($curl), true)['types'];
    $type1 = $typeArray[0]['type']['name'];
    $type2 = $typeArray[1]['type']['name'] ?? 'none';


    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://pokeapi.co/api/v2/pokemon-species/' . ($i + 1),
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $description = json_decode(curl_exec($curl), true)['flavor_text_entries'][0]['flavor_text'];
    $description = str_replace('', ' ', $description);

    $stmt->bindParam(':pokedex_nr', $pokedex_nr);
    $stmt->bindParam(':name', $name);
    $stmt->bindParam(':caught', $caught);
    $stmt->bindParam(':type_1', $type1);
    $stmt->bindParam(':type_2', $type2);
    $stmt->bindParam(':description', $description);
    $stmt->execute();
}
