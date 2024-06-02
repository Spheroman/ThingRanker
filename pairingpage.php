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
//TODO: add form to add a thing mid comp if enabled

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if(!isset($_SESSION["pairing" . $id])) {
    $curr = Pairing::fromRandom($id, $pdo);
} else $curr = unserialize($_SESSION["pairing" . $id]);
if($curr->iscomplete){
    $curr = Pairing::fromRandom($id, $pdo);
}

$_SESSION["pairing". $id] = serialize($curr);
$p1 = $curr->p1;
$p2 = $curr->p2;


echo "<form action='/winner.php' method='POST'>
<input type='hidden' name='redirect' value='pairing/'>
<input type='hidden' name='id' value=$id>
<button name='winner' value='$p1->id'>$p1</button>
<button name='winner' value='$p2->id'>$p2</button>
</form>";