<?php
// page.com/setup/[id]
require "comp.php";
/*TODO: proper HTML and CSS,
  TODO: add other options such as ranking method and pairing method
  TODO: add a pin to lock the setup page
  TODO: add options to add items, delete items, reset the competition, to delete the competition, and to reset the deletion timer (just update in database)
*/
echo "<table style='border: solid 1px black;'>";
echo "<tr><th>Rank</th><th>Name</th><th>Rating</th><th>Confidence</th></tr>";

$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";
$id = $_GET["id"];

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if (isset($_POST['name'])){
        $conn->exec("INSERT INTO $id (name) VALUES ('" .$_POST['name'] . "')");
    }
    $sql = $conn->prepare("SELECT id, name, rating, confidence FROM $id;");
    $sql->execute();

    // set the resulting array to associative
    $result = $sql->setFetchMode(PDO::FETCH_ASSOC);
    $arr = $sql->fetchAll();
    usort($arr, fn($a, $b) => $a['rating'] <=> $b['rating']); //this can be done with sql
    $i = 0;
    foreach ($arr as $v) {
        $i++;
        $a = $v["name"];
        $b = $v["rating"];
        $c = $v["confidence"];
        echo "<tr><td>$i</td><td>$a</td><td>$b</td><td>$c</td></tr>\n";
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
$conn = null;

//will just send post to itself to add things to database. makes it annoying to refresh the page.
echo "</table>";
echo "<form action='' method='POST'>
thingy Name: <label>
    <input type='text' name='name'>
</label><br>
<input type='submit'>
</form>";
