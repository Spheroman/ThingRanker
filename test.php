<?php
require "comp.php";
$servername = "localhost";
$username = "root";
$password = "billybob";
$sql = "";


try {
    $conn = new PDO("mysql:host=$servername;dbname=test", $username, $password);
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $comp = new comp($_POST["name"]);

    $sql = "DROP TABLE IF EXISTS $comp->id;";
    $conn->exec($sql);

    $tmp = $comp->id . "(id)";
    $sql = "CREATE TABLE $comp->id (
        id int PRIMARY KEY NOT NULL AUTO_INCREMENT,
        name VARCHAR(50) NOT NULL,
        elo SMALLINT NOT NULL
    );";
    $conn->exec($sql);
    $sql = "CREATE TABLE $comp->id"."_h2h (
        p1 int,
        p2 int,
        winner int,
        FOREIGN KEY(p1) REFERENCES $tmp,
        FOREIGN KEY(p2) REFERENCES $tmp,
        FOREIGN KEY(winner) REFERENCES $tmp
    );";
    $conn->exec($sql);
    $_SESSION["comp"] = $comp;

    $sql = "INSERT INTO comps (id, name, time) VALUES ('$comp->id', '$comp->name',  now())";
    $conn->exec($sql);

    $sname = $_SERVER['SERVER_NAME'];
    header("Location: /setup.php?id=$comp->id");

} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
