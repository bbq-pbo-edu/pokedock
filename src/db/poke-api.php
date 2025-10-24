<?php

function getPokemonNames(CurlHandle $curl, int $limit): array {
    curl_setopt_array($curl, array(
        CURLOPT_URL => 'https://pokeapi.co/api/v2/pokemon/?limit=' . $limit,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
    ));

    $responseArray = json_decode(curl_exec($curl), true);
    $pokemonArray = $responseArray['results'];
    $names = [];

    foreach ($pokemonArray as $pokemon) {
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pokeapi.co/api/v2/pokemon-species/' . $pokemon['name'],
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $namesArray = json_decode(curl_exec($curl), true)['names'];

        foreach ($namesArray as $name) {
            if ($name['language']['name'] === 'de') {
                $names[] = $name['name'];
                break;
            }
        }
    }
    return $names;
}

function getPokemonTypes(CurlHandle $curl, array $pokemonNames): array {
    $types = [];

    for ($i = 0; $i < count($pokemonNames); $i++) {
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pokeapi.co/api/v2/pokemon/' . $i + 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $responseArray = json_decode(curl_exec($curl), true);
        $typesArray = $responseArray['types'];

        $type1URL = $typesArray[0]['type']['url'];
        $type2URL = $typesArray[1]['type']['url'] ?? null;

        curl_setopt_array($curl, array(
            CURLOPT_URL => $type1URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));

        $typeNamesArray = json_decode(curl_exec($curl), true)['names'];
        $type1 = '';

        foreach ($typeNamesArray as $name) {
            if ($name['language']['name'] === 'de') {
                $type1 = $name['name'];
                break;
            }
        }

        $type2 = '–';
        if ($type2URL !== null) {
            curl_setopt_array($curl, array(
                CURLOPT_URL => $type2URL,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => '',
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => 'GET',
            ));

            $typeNamesArray = json_decode(curl_exec($curl), true)['names'];
            foreach ($typeNamesArray as $name) {
                if ($name['language']['name'] === 'de') {
                    $type2 = $name['name'];
                    break;
                }
            }
        }

        $types[] = [
            'type_1' => $type1,
            'type_2' => $type2
        ];
    }
    return $types;
}

function getPokemonDescriptions(CurlHandle $curl, array $pokemonNames): array {
    $descriptions = [];
    for ($i = 0; $i < count($pokemonNames); $i++) {
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://pokeapi.co/api/v2/pokemon-species/' . $i + 1,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
        ));
        $responseArray = json_decode(curl_exec($curl), true);
        $descriptionsArray = $responseArray['flavor_text_entries'];

        foreach ($descriptionsArray as $description) {
            if ($description['language']['name'] === 'de') {
                $descriptions[] = str_replace('', ' ', $description['flavor_text']);
                break;
            }
        }
    }
    return $descriptions;
}

function resetPokemonTable(): void {
    $conn = createDbConnection();
    $stmt = $conn->prepare("TRUNCATE TABLE pokemon");
    $stmt->execute();
}

function seedPokemonTable(): void {
    $curl = curl_init();
    $pokemonNames = getPokemonNames($curl, 151);
    $pokemonTypes = getPokemonTypes($curl, $pokemonNames);
    $pokemonDescriptions = getPokemonDescriptions($curl, $pokemonNames);
    resetPokemonTable();

    $conn = createDbConnection();
    $stmt = $conn->prepare("INSERT INTO pokemon (pokedex_nr, name, caught, type_1, type_2, description) VALUES (:pokedex_nr, :name, :caught, :type_1, :type_2, :description)");

    foreach ($pokemonNames as $index => $pokemonName) {
        $pokedexNr = $index + 1;
        $name = $pokemonName;
        $caught = rand(0, 2);
        $type1 = $pokemonTypes[$index]['type_1'];
        $type2 = $pokemonTypes[$index]['type_2'];
        $description = $pokemonDescriptions[$index];

        $stmt->bindParam(':pokedex_nr', $pokedexNr);
        $stmt->bindParam(':name', $name);
        $stmt->bindParam(':caught', $caught);
        $stmt->bindParam(':type_1', $type1);
        $stmt->bindParam(':type_2', $type2);
        $stmt->bindParam(':description', $description);
        $stmt->execute();
    }
}