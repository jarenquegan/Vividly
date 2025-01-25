<?php
try {
    require_once 'time_formatting.php';

    // Define database connection parameters
    $dbHost = 'localhost';
    $dbName = 'vividly';
    $dbCharset = 'utf8mb4';
    $dbUser = 'root';
    $dbPass = '@L03e1t3';

    // Create a PDO instance for db connection
    $conn = new PDO("mysql:host=$dbHost; dbname=$dbName; charset=$dbCharset", $dbUser, $dbPass);

    // Disable only_full_group_by mode
    $conn->exec("SET SESSION sql_mode=(SELECT REPLACE(@@sql_mode,'ONLY_FULL_GROUP_BY',''))");

    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    //echo "Connected successfully";
    
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
?>
