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

//below is all we need to copy for the new tablefunction php file
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = $conn->prepare("SELECT id, name, rating, confidence FROM $id ORDER BY rating DESC;");
    $sql->execute();
    $result = $sql->setFetchMode(PDO::FETCH_ASSOC);
    $arr = $sql->fetchAll();
    usort($arr, fn($a, $b) => $a['rating'] <=> $b['rating']);

    // Display items in the table
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

// Display form to add new items
echo "</table>";
echo "<form action='' method='POST'>
thingy Name: <label>
    <input type='text' name='name'>
</label><br>
<input type='submit' value='Add Item'>
</form>";

// Additional options for the competition setup
echo "<div>";
echo "<h2>Competition Options</h2>";
echo "<ul>";
echo "<li><a href='#'>Ranking Method</a></li>";
echo "<li><a href='#'>Pairing Method</a></li>";
echo "<li><a href='#'>Reset Competition</a></li>";
echo "<li><a href='#'>Delete Competition</a></li>";
echo "</ul>";
echo "</div>";
?>
