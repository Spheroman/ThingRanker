<?php

require "utils.php";

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
        
        
        if(!tableCheck($id, $pdo)){
            throw new Exception("id not found");
        }

    
        // Check if the 'started' column is 0
        $stmt = $pdo->prepare("select started from comps where id = :id;");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetchColumn();

        if (!$result) {
            // Prepare the SQL statement to delete the entry
            $x = $pdo->prepare("DELETE FROM $id (name) WHERE id = :id");
            $x->bindParam(':id', $id);
            $x->execute();
        }


        header("Location: /".$_POST["redirect"].$_POST["id"]);
        exit();

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
}
    

?>
