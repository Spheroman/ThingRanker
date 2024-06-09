<?php

require "utils.php";
require_once "config.php";

$servername="localhost";
$username="root";
$password="billybob";
$dbname="test";

// Check if required POST parameters are set
if (!isset($_POST['id'])) {
    die("Error: Missing 'id' parameter.");
}

if (!isset($_POST['name']) && !isset($_POST['itemid'])) {
    die("Error: Missing either 'name' or 'itemid' parameter.");
}

// Initialize variables
$id = trim($_POST['id']);
$name = isset($_POST['name']) ? trim($_POST['name']) : null;
$itemid = isset($_POST['itemid']) ? trim($_POST['itemid']) : null;
$redirect = isset($_POST['redirect']) ? trim($_POST['redirect']) : '/';

// Validate id
if ($id === '') {
    die("Error: 'id' cannot be empty.");
}

try {
    // Establish database connection
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Check if table exists
    if (!tableCheck($id, $pdo)) {
        throw new Exception("Error: 'id' (table) not found.");
    }

    // Check if the competition has not started
    $stmt = $pdo->prepare("SELECT started FROM comps WHERE id = :id");
    $stmt->bindParam(':id', $id);
    $stmt->execute();
    $started = $stmt->fetchColumn();

    if ($started) {
        throw new Exception("Error: The competition has already started and items cannot be removed.");
    }

    // Prepare and execute the delete statement
    if ($name !== null) {
        $stmt = $pdo->prepare("DELETE FROM $id WHERE name = :name");
        $stmt->bindParam(':name', $name);
    } elseif ($itemid !== null) {
        $stmt = $pdo->prepare("DELETE FROM $id WHERE id = :itemid");
        $stmt->bindParam(':itemid', $itemid);
    }

    $stmt->execute();

    // Redirect on success
    header("Location: $redirect");
    exit();
} catch (PDOException $e) {
    echo "Database Error: " . $e->getMessage();
} catch (Exception $e) {
    echo $e->getMessage();
}
?>
