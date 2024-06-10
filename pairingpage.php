<!DOCTYPE html>
<html>
<head>
<?php
session_start();
require "pairing.php";
require_once "config.php";

$servername = DB_HOST;
$username = DB_USER;
$password = DB_PASS;
$dbname = DB_NAME;

$id = $_GET["id"];
// [id]/pairing
//TODO: add form to add a thing mid comp if enabled

$pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

if (!isset($_SESSION["uuid"])) {
    $_SESSION["uuid"] = generateRandomString(8);
}
if (!isset($_SESSION["pairing" . $id])) {
    if (!tableCheck($id, $pdo)) die("comp not found");
    if (!startedCheck($id, $pdo)) die("comp has not started");
    $curr = Pairing::fromRandom($id, $pdo);
} else $curr = unserialize($_SESSION["pairing" . $id]);
if ($curr->iscomplete) {
    $curr = Pairing::fromRandom($id, $pdo);
}

$stmt = $pdo->prepare("SELECT name FROM comps WHERE id = :id");
$stmt->bindParam(":id", $id);
$stmt->execute();
$name = htmlspecialchars($stmt->fetch(PDO::FETCH_ASSOC)['name']);
echo "<title>$name</title>";

echo '<link href="/pairing.css" rel="stylesheet">
    </head>
    <body>
';
echo "<a href='/$id'><h1>$name</h1></a>";

$_SESSION["pairing" . $id] = serialize($curr);
$p1 = $curr->p1;
$p2 = $curr->p2;


echo "<form name= 'pairing' action='/submit.php' method='POST'><div class ='flexbox'>
<input type='hidden' name='redirect' value='/pairing'>
<input type='hidden' name='id' value=$id>
<button class='flex-item-left' name='winner' value='$p1->id'>$p1</button>
<h2 class = 'flex-item-center'>OR</h2>
<button class='flex-item-right' name='winner' value='$p2->id'>$p2</button></div>
</form>
</body>
</html>";
