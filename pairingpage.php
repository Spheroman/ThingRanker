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

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!isset($_SESSION["pairing"])){
    $curr = Pairing::fromRandom($id, $pdo);
} else{
    try {
        $curr = Pairing::fromSQL($id, $_SESSION["pairing"], $pdo);
    } catch (PDOException $e){
        echo $e;
        $curr = Pairing::fromRandom($id, $pdo);
    }
}

if($curr->iscomplete){
    $curr = Pairing::fromRandom($id, $pdo);
}

$_SESSION["pairing"] = $curr->id;

echo $curr->id;


