<?php
session_start();
require "pairing.php";


$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";


if (!isset($_POST['winner']) || !isset($_POST['id']) || !isset($_SESSION['pairing'])) {
    die("Missing 'name' or 'id' parameters, or pairing has not been issued yet.");
}



//Initialize name and id
$winner = $_POST['winner'];
$id = $_POST['id'];

$pairing = unserialize($_SESSION["pairing".$id]);
$pairing->setWinner($winner);

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    if(!tableCheck($id, $pdo)){
        throw new Exception("id not found");
    }

    $pairing->calculate($pdo)->update($pdo);

    $_SESSION["pairing".$id] = serialize(Pairing::fromRandom($id, $pdo));
    header("Location: /".$_POST["redirect"].$_POST["id"]);
    exit();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
