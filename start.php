<?php
require "tablechecker.php";

$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";

if (!isset($_POST['id']) || !isset($_POST['redirect'])) {
    die("Missing 'id' or 'redirect' parameter.");
}

$id = $_POST['id'];

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if(!tableCheck($id, $pdo)){
        throw new Exception("id not found");
    }

    $tmp = $id . "(id)";
    $stmt = $pdo->prepare("
        DROP TABLE IF EXISTS $id"."_h2h;
        CREATE TABLE $id" . "_h2h (
        id int AUTO_INCREMENT PRIMARY KEY,
        p1 int NOT NULL,
        p2 int NOT NULL,
        winner BOOLEAN,
        player VARCHAR(20), 
        iscomplete BOOLEAN NOT NULL DEFAULT FALSE,
        FOREIGN KEY(p1) REFERENCES $tmp,
        FOREIGN KEY(p2) REFERENCES $tmp
    );
    UPDATE comps SET started = 1 WHERE id = :id
    ");
    $stmt->bindParam(":id", $id);
    $stmt->execute();

    header("Location: /".$_POST["id"].$_POST["redirect"]);
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}