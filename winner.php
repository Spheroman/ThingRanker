<?php
session_start();
require "pairing.php";


$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";


if (!isset($_POST['winner']) || !isset($_POST['id'])) {
    die("Missing 'name' or 'id' parameters.");
}





//Initialize name and id
$winner = $_POST['winner'];
$id = $_POST['id'];

if(!isset($_SESSION['pairing'.$id])) die("missing pairing");

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
