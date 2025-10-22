<?php

function createDBConnection(string $host=DB_HOST, string $user=DB_USER, string $pass=DB_PASS, string $dbname=DB_NAME): PDO|null {
    try {
        return new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $user, $pass);
    }
    catch (PDOException $e) {
        echo "Connection to database failed.<br>" . $e->getMessage() . "<br>";
        return null;
    }
}

function findAll(string $tableName): array {
    $conn = createDBConnection();
    $stmt = $conn->prepare("SELECT * FROM $tableName");
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function findById(string $tableName, int $id): array {
    $conn = createDBConnection();
    $stmt = $conn->prepare("SELECT * FROM $tableName WHERE id = :id");
    $stmt->bindParam(":id", $id);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function deleteById(string $tableName, int $id): bool {
    $conn = createDBConnection();
    $stmt = $conn->prepare("DELETE FROM $tableName WHERE id = :id");
    $stmt->bindParam(":id", $id);
    return $stmt->execute();
}

function updateById(string $tableName, int $id, array $data): bool {
    $conn = createDBConnection();
    $columns = array_keys($data);

    foreach ($columns as $column) {
        $stmt = $conn->prepare("UPDATE {$tableName} SET $column = ? WHERE id = ?");
        $stmt->execute([$data[$column], $id]);
    }
    return true;
}

function create(string $tableName, array $data): bool {
    $conn = createDBConnection();

    $columnsArray = array_keys($data);
    $columnsFormatted = implode(', ', $columnsArray);
    $numColumns = count($columnsArray);
    $columnPlaceHolders = str_repeat('?,', $numColumns - 1) . '?';

    $valuesArray = array_values($data);

    $sql = "INSERT INTO {$tableName} ($columnsFormatted) VALUES ({$columnPlaceHolders})";

    $stmt = $conn->prepare($sql);
    return $stmt->execute($valuesArray);
}

function findLatestId(string $tableName): int {
    $conn = createDBConnection();
    $stmt = $conn->prepare("SELECT * FROM $tableName ORDER BY id DESC LIMIT 1");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['id'];
}