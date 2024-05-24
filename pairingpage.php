<?php
session_start();
require "pairing.php";

$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";
// page.com/pairing/[id]
//TODO: page for a pairing. needs to be pretty
//TODO: store pairing class in session vars to prevent cheesing by refreshing the page.
//TODO: add form to add a thing mid comp if enabled

$curr = null;

if(!isset($_SESSION["pairing"])){
    $curr = new Pairing();
} else{
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $curr = Pairing::fromSQL($_GET["id"], $_SESSION["pairing"], $conn);
    } catch (PDOException $e){
        $curr = new Pairing();
    }
}

if($curr->iscomplete){
    $curr = new Pairing();
}


