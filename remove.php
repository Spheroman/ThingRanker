<?php
//hidden from users
//TODO: remove an item from comp, but only if the comp hasn't started yet.


$servername = "localhost";
$username = "root";
$password = "billybob";
$dbname = "test";


if (!isset($_POST['name']) || !isset($_POST['id'])) {
    die("Missing 'name' or 'id' parameters.");
}

//Initialize name and id
$name = $_POST['name'];





try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Sanitize and validate the input
   
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $redirect = filter_var($_POST['redirect'], FILTER_SANITIZE_URL);
    $postId = filter_var($_POST['id'], FILTER_SANITIZE_STRING);

    // Prepare the SQL statement with placeholders
    $stmt = $pdo->prepare("DELETE FROM your_table WHERE name = :name");

    // Bind the sanitized value to the placeholder
    $stmt->bindParam(':name', $name);

    // Execute the prepared statement
    $stmt->execute();

    // Construct the redirect URL with sanitized values
    $redirectUrl = "/$redirect/$postId";

    // Redirect to the specified URL
    header("Location: $redirectUrl");
    exit();
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}


?>
