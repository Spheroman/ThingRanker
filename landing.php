<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html; charset=utf-8">
        <link rel="stylesheet" href="landing.css">
        <link rel="stylesheet" href="fonts.css">
        <link rel="stylesheet" href="landinglayout.css">
        <link rel="stylesheet" href="resultstable.css">
<?php
require "comp.php";
require_once "config.php";
$servername = DB_HOST;
$username = DB_USER;
$password = DB_PASS;
$dbname = DB_NAME;

$id = $_GET["id"];

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// page.com/[id]
//TODO: comp landing page.
//TODO: option to set name or to add things(if options enabled).
// add link to go to setup page as well

if(!tableCheck($id, $conn)) {
    echo "comp not found";
    return "error";
}

$sql = $conn->prepare("select name, started, passcode, publicadd, addwhilerun, playerlimit, pairingtype, maxrounds from comps where id = :id");
$sql->bindParam(":id", $id);
$sql->execute();
$comp = $sql->fetchObject(class: "comp");

echo "<title>$comp->name</title>     
    </head>
    <a>
        <h1>$comp->name</h1>
        <a href='$id/setup'>
        <input type='button' class='gear' value='🛠'> </a>
        <section>
            <div>
            <table>";
generateTable($id, $conn);
echo "            </table></div>
        </section>";
if($comp->started)
    echo "<a href='$id/pairing'><input class = 'start' type='submit' value='START'></a>";
echo "</body>
</html>";

