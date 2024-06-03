<?php
require "tablechecker.php";

$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";


if (!isset($_POST['name']) || !isset($_POST['id'])) {
    die("Missing 'name' or 'id' parameters.");
}

//Initialize name and id
$name = $_POST['name'];
$id = $_POST['id'];

if(trim($name) == ''){
    die("name cannot be empty");
}

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if(!tableCheck($id, $pdo)){
        throw new Exception("id not found");
    }

    $stmt = $pdo->prepare("INSERT INTO $id (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    header("Location: /".$_POST["id"].$_POST["redirect"]);
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
