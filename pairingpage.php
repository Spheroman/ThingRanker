<?php
session_start();
require "pairing.php";

$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";
$id = $_GET["id"];
// page.com/pairing/[id]
//TODO: page for a pairing. needs to be pretty
//TODO: store pairing class in session vars to prevent cheesing by refreshing the page.
//TODO: add form to add a thing mid comp if enabled

$curr = null;
$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
echo("making pairing");

try {
    $curr = Pairing::fromRandom($id, $pdo);
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8');
}

echo($curr->id);

/*

if(!isset($_SESSION["pairing"])){
    $curr = Pairing::fromRandom($id, $pdo);
} else{
    try {
        $curr = Pairing::fromSQL($id, $_SESSION["pairing"], $pdo);
    } catch (PDOException $e){
        $curr = Pairing::fromRandom($id, $pdo);
    }
}

if($curr->iscomplete){
    $curr = Pairing::fromRandom($id, $pdo);
}

*/

