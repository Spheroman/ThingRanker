<?php

//TODO: add $_POST["name"] to item table, and update the date in the comps table
//TODO: sql injection protection

//This is incomplete (obviously). I just got hungry as I was coding this so brb

$value = $_POST['fieldname'];

$stmt = $pdo->prepare("SELECT * FROM comps WHERE name = :name AND id = :id");
$stmt->execute(['name' => $name, 'id' => $id]);
$check = $stmt->fetch();

if (!$check) {
  echo "Invalid name or id";
} else {
  try{
    $servername = "localhost";
    $username = "root";
    $password = "billybob";
    $dbname = "test";

    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);

    $stmtid = $pdo->prepare("INSERT INTO comps (id) VALUES (:id)");
    $stmtname = $pdo->prepare("INSERT INTO comps (name) VALUES (:name)");
    $stmtdate = $pdo->prepare("INSERT INTO comps (time) VALUES (:time)");

    $stmtid->bindParam(':id', $id);
    $stmtname->bindParam(':name', $name);
    $stmtdate->bindParam(':time', $time);

    $stmtid->execute();
    $stmtname->execute();
    $stmtdate->execute();
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
}


header("Location: /setup/" . $_POST["id"]); //redirect to /setup page
