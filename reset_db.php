<?php
$host = '127.0.0.1';
$port = '3306';
$user = 'root';
$pass = '';

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $pass);
    $stmt = $pdo->query("SHOW VARIABLES LIKE 'datadir'");
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $datadir = $row['Value'];
    echo "Datadir is: " . $datadir . "\n";
    $dbdir = $datadir . 'evuba';
    echo "DB dir is: " . $dbdir . "\n";
    if (is_dir($dbdir)) {
        $files = scandir($dbdir);
        print_r($files);
    } else {
        echo "DB dir does not exist.\n";
    }
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
