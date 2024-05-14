<?php
require "comp.php";
//basically have to show a table with all the things along with stats
//options to remove, add, reset, and delete

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
echo "<form action='setup.php?id=$id' method='POST'>
thingy Name: <label>
    <input type='text' name='name'>
</label><br>
<input type='submit'>
</form>";
