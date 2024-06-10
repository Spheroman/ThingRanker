<?php
require "utils.php";
require_once "config.php";

$servername = DB_HOST;
$username = DB_USER;
$password = DB_PASS;
$dbname = DB_NAME;

// Function to validate table names
function isValidTableName($id) {
    return preg_match('/^[a-zA-Z0-9_]+$/', $id);
}

// Function to validate competition/item names
function isValidName($name) {
    return preg_match('/^[a-zA-Z0-9\s]+$/', $name);
}

if (!isset($_POST['name']) || !isset($_POST['id'])) {
    die("Missing 'name' or 'id' parameters.");
}

//Initialize name and id
$name = trim($_POST['name']);
$id = trim($_POST['id']);

//Validate name is valid
if ($name === '') {
    die("Error: 'name' cannot be empty.");
}

if (!isValidName($name)) {
    die("Error: 'name' contains invalid characters.");
}

if (strlen($name) > 50) {
    die("Error: 'name' is too long. Maximum length is 50 characters.");
}

//Validate if id is valid
if ($id === '') {
    die("Error: 'id' cannot be empty.");
}

if (!isValidTableName($id)) {
    die("Error: 'id' contains invalid characters.");
}


try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if(!tableCheck($id, $pdo)){
        throw new Exception("id not found");
    }

    // Check for duplicate name
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM $id WHERE name = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    if ($stmt->fetchColumn() > 0) {
        throw new Exception("Error: 'name' already exists in the table.");
    }

    $stmt = $pdo->prepare("INSERT INTO $id (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    header("Location: /".$_POST["id"].$_POST["redirect"]);
    exit();
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
}catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
