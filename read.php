<?php
$host = 'localhost';
$db   = 'mtg';
$user = 'root';
$pass = 'root';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    throw new \PDOException($e->getMessage(), (int)$e->getCode());
}

$stmt = $pdo->query('SELECT * FROM coreSet2020');
while ($row = $stmt->fetch()){
    echo $row['cardName'] . "<br />";

}


?>