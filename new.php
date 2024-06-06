<?php
//hidden from users
require "comp.php";
require_once "config.php";

$servername = DB_HOST;
$username = DB_USER;
$password = DB_PASS;
$dbname = DB_NAME;



//TODO: maybe add a pin to lock the setup page, better ID logic
try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $comp = comp::fromName($_POST["name"]);

    //TODO: change logic to only overwrite expired tables (comp last modified 1 yr ago)
    $sql = "
    DROP TABLE IF EXISTS $comp->id;
    CREATE TABLE $comp->id (
        id int auto_increment primary key,
        name varchar(50) not null,
        rating smallint default 1000,
        variance smallint default 500
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
    INSERT INTO comps (id, name) VALUES (:comp_id, :comp_name)
    ";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':comp_id', $comp->id);
    $stmt->bindParam(':comp_name', $comp->name);
    $stmt->execute();

    header("Location: /$comp->id/setup");

} catch (Exception $e) {
    error_log("Error: " . $e->getMessage());
    echo "An error has occurred: " . $e->getMessage();
}

$conn = null;