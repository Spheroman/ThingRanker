<?php

//TODO: add $_POST["name"] to item table, and update the date in the comps table
//TODO: sql injection protection

if (!isset($_POST['name']) || !isset($_POST['id'])) {
    die("Missing 'name' or 'id' parameters.");
}

//Initialize name and id
$name = $_POST['name'];
$id = $_POST['id'];

try {
    $stmt = $pdo->prepare("INSERT INTO $id (name) VALUES (:name)");
    $stmt->bindParam(':name', $name);
    $stmt->execute();

    header("Location: /setup/".$_POST["id"]);
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>
