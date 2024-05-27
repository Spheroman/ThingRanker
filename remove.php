<?php

$servername="localhost";
$username="root";
$password="billybob";
$dbname="test";



    if (!isset($_POST['name']) || !isset($_POST['id'])) {
            die("Missing 'name' or 'id' parameters.");
        }

        $name=$_POST['name'];
        $id=$_POST['id'];

 try {
    // Create a new PDO instance
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

 
    // Check if the 'started' column is 0
    $stmt = $pdo->prepare("SELECT started FROM $id(name) WHERE name = :name");
    $stmt->bindParam(':name', $name);
    $stmt->execute();
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result && $result['started'] == 0) {
        // Prepare the SQL statement to delete the entry
        $x = $pdo->prepare("DELETE FROM $id(name) WHERE name = :name");
        $x->bindParam(':name', $name);
        $x->execute();
    }\

     header("Location: /".$_POST["redirect"].$_POST["id"]);
     exit();

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

?>
