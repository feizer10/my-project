<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "Testing MySQL connection...<br>";

try {
    $mysqli = new mysqli('127.0.0.1', 'root', '', '', 3322);
    
    if ($mysqli->connect_error) {
        throw new Exception("Connect Error ({$mysqli->connect_errno}) {$mysqli->connect_error}");
    }
    
    echo "Connected successfully to MySQL!<br>";
    echo "MySQL version: " . $mysqli->server_info . "<br>";
    
    // Пробуем создать базу данных
    if ($mysqli->query("CREATE DATABASE IF NOT EXISTS airline_booking")) {
        echo "Database airline_booking created or already exists<br>";
    } else {
        echo "Error creating database: " . $mysqli->error . "<br>";
    }
    
    // Выбираем базу данных
    if ($mysqli->select_db('airline_booking')) {
        echo "Selected database airline_booking<br>";
    } else {
        echo "Error selecting database: " . $mysqli->error . "<br>";
    }
    
    // Проверяем существующие таблицы
    $result = $mysqli->query("SHOW TABLES");
    if ($result) {
        echo "Tables in database:<br>";
        while ($row = $result->fetch_array()) {
            echo "- " . $row[0] . "<br>";
        }
    } else {
        echo "Error showing tables: " . $mysqli->error . "<br>";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
} 