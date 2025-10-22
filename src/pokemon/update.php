<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $data = $_POST;
    updateById('pokemon', $id, $data);

    header('Location: /pokemon/show/' . $id);
    exit();
}
