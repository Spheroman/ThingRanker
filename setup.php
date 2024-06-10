<!DOCTYPE html>
<html lang="en">
    <head>
        <meta http-equiv="content-type" charset=utf-8" name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="/layout.css">
        <link rel="stylesheet" href="/setup.css">
        <link rel="stylesheet" href="/setuptable.css">
        <link rel="stylesheet" href="/fonts.css">

<?php
// page.com/setup/[id]
require "comp.php";
require_once "config.php";

/*TODO: proper HTML and CSS,
  TODO: add other options such as ranking method and pairing method
  TODO: add a pin to lock the setup page
  TODO: add options to add items, delete items, reset the competition, to delete the competition, and to reset the deletion timer (just update in database)
*/

$servername = DB_HOST;
$username = DB_USER;
$password = DB_PASS;
$dbname = DB_NAME;

$id = $_GET["id"];

$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

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
    <body>
        <a href='/$id'><h1>$comp->name</h1></a> <section>
            <div>
            <table>";
$count = generateTable($id, $conn, false);
echo "

</table></div>
        </section> <form action='/add.php' method='POST'>
<label>
    <input type='text' name='name' autofocus autocomplete='off' class='add'>
</label><br>
<input type='hidden' name='redirect' value='/setup'>
<input type='hidden' name='id' value=$id>
<button type='submit' class='button'>Add Item</button>
</form>";

if($count > 2) {
echo "<form action='/start.php' method='POST'>
<input type='hidden' name='redirect' value='/pairing'>
<input type='hidden' name='id' value=$id>
<button type='submit' onclick='clicked(event)' class='start'>";

    if (!$comp->started) echo "START!";
    else echo "RESTART!";

    echo "</button>
</form>";
}
echo "</body>
</html>

<script>
function clicked(e)
{
    if(!confirm('Start the competition?')) {
        e.preventDefault();
    }
}
</script>";
