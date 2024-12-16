<?php
$dsn = "pgsql:host=localhost;port=5432;dbname=ilearnova;";
$user = "babangidxa";
$pass = "Bbg72720232@$";
//use postgrest credentials above


try {
    $pdo = new PDO($dsn, $user, $pass, [ PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

return $pdo;