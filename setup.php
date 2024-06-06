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

if (!tableCheck($id, $conn)) {
    echo "comp not found";
    return "error";
}

$sql = $conn->prepare("select name, started, passcode, publicadd, addwhilerun, playerlimit, pairingtype, maxrounds from comps where id = :id");
$sql->bindParam(":id", $id);
$sql->execute();
$comp = $sql->fetchObject(class: "comp");

echo "<h1>$comp->name</h1>";

echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Rank</th><th>Name</th><th>Rating</th><th>Confidence</th></tr>";

//below is all we need to copy for the new tablefunction php file
try {
    $sql = $conn->prepare("SELECT id, name, rating, confidence FROM $id ORDER BY rating DESC;");
    $sql->execute();
    $sql->setFetchMode(PDO::FETCH_ASSOC);
    $arr = $sql->fetchAll();
    $i = 0;
    foreach ($arr as $v) {
        $i++;
        $a = htmlspecialchars($v["name"], ENT_NOQUOTES, 'UTF-8');
        $b = htmlspecialchars($v["rating"], ENT_NOQUOTES, 'UTF-8');
        $c = htmlspecialchars($v["confidence"], ENT_NOQUOTES, 'UTF-8');
        echo "<tr><td>$i</td><td>$a</td><td>$b</td><td>$c</td></tr>\n";
    }
} catch (PDOException $e) {
    echo "SQL Error: " . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8');
} catch (Exception $e) {
    echo "Error: " . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8');
}
$conn = null;

echo "</table>";
echo "<form action='/add.php' method='POST'>
thing name: <label>
    <input type='text' name='name' autofocus autocomplete='off'>
</label><br>
<input type='hidden' name='redirect' value='/setup'>
<input type='hidden' name='id' value=$id>
<button type='submit'>add item</button>
</form>";

$check['live'] = $comp->addwhilerun == 1 ? 'checked' : '';
$check['pubadd'] = $comp->publicadd == 1 ? 'checked' : '';
$check['captog'] = $comp->playerlimit != -1 ? 'checked' : '';

echo "
<h2>Options</h2>
<form action='/options.php' method='POST'>
<input type='checkbox' id='pubadd' name='pubadd' value='1' {$check['pubadd']}>
<label for='pubadd'>Allow anyone to add items to the rankings</label><br>
<input type='checkbox' id='live' name='live' value='1' {$check['live']}>
<label for='live'>Allow additions to the list while rankings are running</label><br>
<input type='checkbox' id='playercap' name='playercap' value=1' {$check['captog']}>
<label for='playercap'>Limit number of players</label>
<input type='number' id='capcount' name='capcount' min='1' value='{$comp->playerlimit}' style='display: none'>
</form>

";

echo "<form action='/start.php' method='POST'>
<input type='hidden' name='redirect' value='/pairing'>
<input type='hidden' name='id' value=$id>
<button type='submit' onclick='clicked(event)'>start comp</button>
</form>";

echo "<script>
$(function () {
    $('#playercap').click(function () {
        if ($(this).is(':checked')) {
            $('#capcount').show();
        } else {
            $('#capcount').hide();
        }
    });
});
function clicked(e)
{
    if(!confirm('Start the competition?')) {
        e.preventDefault();
    }
}
</script>";
