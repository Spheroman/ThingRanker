<?php
//hidden from users
require "comp.php";
$servername = "localhost";
$username = "root";
$password = "billybob";
$sql = "";


//TODO: maybe add a pin to lock the setup page, better ID logic
//TODO: sql injection protection
try {
    $conn = new PDO("mysql:host=$servername;dbname=test", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $comp = new comp($_POST["name"]);

    //TODO: change logic to only overwrite expired tables (comp last modified 1 yr ago)
    $sql = "DROP TABLE IF EXISTS $comp->id;";
    $conn->exec($sql);
    $tmp = $comp->id . "(id)";
    //generate items table
    $sql = "CREATE TABLE $comp->id (
        id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        rating SMALLINT NOT NULL,
        confidence SMALLINT NOT NULL
    );";
    $conn->exec($sql);
    //TODO: move match history table generation to when the competition starts
    //generate match history table
    $sql = "CREATE TABLE $comp->id"."_h2h (
        p1 int NOT NULL,
        p2 int NOT NULL,
        winner int NOT NULL,
        player VARCHAR(20) NOT NULL, 
        FOREIGN KEY(p1) REFERENCES $tmp,
        FOREIGN KEY(p2) REFERENCES $tmp,
        FOREIGN KEY(winner) REFERENCES $tmp
    );";
    $conn->exec($sql);
    $_SESSION["comp"] = $comp;

    $sql = "INSERT INTO comps (id, name, time) VALUES ('$comp->id', '$comp->name',  now())";
    $conn->exec($sql);

    $sname = $_SERVER['SERVER_NAME'];
    header("Location: /setup/$comp->id");

} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
