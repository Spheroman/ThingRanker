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
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $tmp = $comp->id . "(id)";
    //generate items table
    $sql = "
    CREATE TABLE $comp->id (
        id int auto_increment primary key,
        name varchar(50) not null,
        rating smallint default 1000,
        confidence smallint default 500
    );
    CREATE trigger $comp->id" . "trigger
    AFTER insert
    ON $comp->id
    FOR EACH ROW
    BEGIN
        UPDATE comps
        SET updated = NOW()
        WHERE id = '$comp->id';
    END;
    ";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    //TODO: move match history table generation to when the competition starts
    //generate match history table
    $sql = "CREATE TABLE $comp->id" . "_h2h (
        id int AUTO_INCREMENT PRIMARY KEY,
        p1 int NOT NULL,
        p2 int NOT NULL,
        winner BOOLEAN,
        player VARCHAR(20), 
        iscomplete BOOLEAN NOT NULL DEFAULT FALSE,
        FOREIGN KEY(p1) REFERENCES $tmp,
        FOREIGN KEY(p2) REFERENCES $tmp
    );";
    $stmt = $conn->prepare($sql);
    $stmt->execute();

    $_SESSION["comp"] = $comp;

    $sql = "INSERT INTO comps (id, name) VALUES (:comp_id, :comp_name)";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':comp_id', $comp->id);
    $stmt->bindParam(':comp_name', $comp->name);
    $stmt->execute();

    header("Location: /setup/$comp->id");

} catch (PDOException $e) {
    echo $sql . "<br> Database error: " . $e->getMessage();
} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo "An error has occurred: " . $e->getMessage();
}

$conn = null;
?>
