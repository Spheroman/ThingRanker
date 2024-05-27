<?php

$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";

//TODO: add $_POST["name"] to item table, and update the date in the comps table
//TODO: sql injection protection

if (!isset($_POST['name']) || !isset($_POST['id'])) {
    die("Missing 'name' or 'id' parameters.");
}

//Initialize name and id
$name = $_POST['name'];
$id = $_POST['id'];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if(!tableCheck($id, $pdo)){
        throw new Exception("id not found");
    }

    $stmt = $pdo->prepare("INSERT INTO $id (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    header("Location: /".$_POST["redirect"].$_POST["id"]);
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
