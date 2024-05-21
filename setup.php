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
        $name = $_POST['name'];
        $stmt = $conn->prepare("INSERT INTO $id (name) VALUES (:name)");
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->execute();
    }
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

//will just send post to itself to add things to database. makes it annoying to refresh the page.
echo "</table>";
echo "<form action='/add.php' method='POST'>
thingy Name: <label>
    <input type='text' name='name'>
</label><br>
<input type='hidden' name='redirect' value='setup/'>
<input type='hidden' name='id' value=$id>
<input type='submit'>
</form>";
