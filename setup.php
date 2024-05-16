<?php
// page.com/setup/[id]
require "comp.php";
/*TODO: proper HTML and CSS,
  TODO:add other options such as ranking method and pairing method
  TODO: add a pin to lock the setup page
  TODO: add options to add items, delete items, reset the competition, to delete the competition, and to reset the deletion timer (just update updated in database)
*/
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Rank</th><th>Name</th><th>Elo</th></tr>";

$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";
$id = $_GET["id"];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $conn->prepare("SELECT id, name, elo FROM $id;");
    $stmt->execute();

    // set the resulting array to associative
    $result = $stmt->setFetchMode(PDO::FETCH_ASSOC);
    $arr = $stmt->fetchAll();
    usort($arr, fn($a, $b) => $a['elo'] <=> $b['elo']);
    $i = 0;
    foreach ($arr as $v) {
        $i++;
        $a = $v["name"];
        $b = $v["elo"];
        echo "<tr><td>$i</td><td>$a</td><td>$b</td></tr>\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;
echo "</table>";
echo "<form action='add.php' method='POST'>
thingy Name: <label>
    <input type='text' name='name'>
    <input type='text' name='id'>
</label><br>
<input type='submit'>
</form>";
