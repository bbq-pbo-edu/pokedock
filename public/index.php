<?php

require_once '../config/loader.php';

$request = explode('/', strtolower(trim($_SERVER['REQUEST_URI'], '/')));

$entity = $request[0] ?? null;
$method = $request[1] ?? null;
$id = $request[2] ?? null;

if ($entity === 'api') {
    $entity = $request[1] ?? null;
    $method = $_SERVER['REQUEST_METHOD'] ?? null;
    $id = $request[2] ?? null;

    switch ($method) {
        case 'GET':
            if ($id) {
                header('Content-type: application/json');
                echo json_encode(['data' => findById('pokemon', $id)]);
            }
            else {
                header("Content-type: application/json");
                echo json_encode(['data' => findAll('pokemon')]);
            }
            break;

        case 'POST':
            $data = $_POST;
            create('pokemon', $data);
            header('Content-type: application/json');
            echo json_encode(['data' => findById('pokemon', findLatestId('pokemon'))]);
            break;

        case 'PUT':
            $data = file_get_contents('php://input');
            $data = json_decode($data, true);
            updateById('pokemon', $id, $data);
            header('Content-type: application/json');
            echo json_encode(['data' => findById('pokemon', $id)]);
            break;

        case 'DELETE':
            deleteById('pokemon', $id);
            break;
    }
}

else if (empty($entity)) {
    require_once '../view/welcome.php';
}
else if ($entity === 'pokemon' && empty($method)) {
    require_once '../view/welcome.php';
}
else if ($entity === 'pokemon' && $method === 'create') {
    require_once '../src/pokemon/create.php';
}
else if ($entity === 'pokemon' && $method === 'read') {
    require_once '../src/pokemon/read.php';
}
else if ($entity === 'pokemon' && $method === 'show') {
    require_once '../src/pokemon/show.php';
}
else if ($entity === 'pokemon' && $method === 'update') {
    require_once '../src/pokemon/update.php';
}
else if ($entity === 'pokemon' && $method === 'delete') {
    require_once '../src/pokemon/delete.php';
}
else if ($entity === 'pokemon' && $method === 'edit') {
    require_once '../src/pokemon/edit.php';
}
else if ($entity === 'pokemon' && $method ==='add') {
    require_once '../src/pokemon/add.php';
}
else if ($entity === 'seed') {
    seedPokemonTable();
    header('Location: /pokemon');
    exit();
}
else if ($entity === '404') {
    require_once '../view/404.php';
}
else {
    header('Location: /404');
    exit();
}