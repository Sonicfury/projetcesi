<?php
error_reporting(E_ALL);
ini_set('max_execution_time', -1);

require_once dirname(__DIR__) . '\config\config.php';

function connect($withDbName){
    $dsn = !$withDbName ? 'mysql:host=' . DB_HOST . ';port=' . DB_PORT : DB_DSN;
    try {
        $db = new PDO($dsn, DB_USER, DB_PASS);
    } catch (\PDOException $e) {
        echo 'Error : ' . $e->getMessage() . "\r\n";
    }
    return $db;
}

function create(){
    $withDbName = false;
    $db = connect($withDbName);

    echo "ConnectLife Database creation script: \r\n";

    try {
        $stmt = utf8_encode(file_get_contents(__DIR__ . '\connectlife.sql', FALSE, NULL));
        echo "SQL queries fetched ! \r\n";
        echo "creating database... \r\n";
        $db->exec($stmt);
        echo "... complete ! \r\n";
    } catch (Exception $e) {
        echo 'Error : ' . $e->getMessage() . "\r\n";
    }
}

function populate(){
    $withDbName = true;
    $db = connect($withDbName);

    echo "populating database... \r\n";

    try {
        $handle = fopen(__DIR__ . '\connectlife_insee.sql', 'rb');
        if ($handle) {
            while (($buffer = fgets($handle)) !== false) {
                $db->exec(utf8_encode($buffer));
                echo $buffer;
            }
            if (!feof($handle)) {
                echo "Error : fgets() error \r\n";
            }
            fclose($handle);
        }
        echo "... complete ! \r\n";
    } catch (Exception $e) {
        echo 'Error : ' . $e->getMessage() . "\r\n";
    }
}

create();
populate();