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
    $sql = "
CREATE TABLE $comp->id (
    id int auto_increment primary key,
    name varchar(50) not null,
    rating smallint default 1000,
    confidence smallint default 500
);
create trigger $comp->id" . "trigger
    after insert
    on $comp->id
    for each row
begin
    update comps
    set updated = NOW()
    where id = '$comp->id';
end;
";
    $conn->exec($sql);
    //TODO: move match history table generation to when the competition starts
    //generate match history table
    $sql = "CREATE TABLE $comp->id" . "_h2h (
        id int AUTO_INCREMENT PRIMARY KEY,
        p1 int NOT NULL,
        p2 int NOT NULL,
        winner int,
        player VARCHAR(20), 
        FOREIGN KEY(p1) REFERENCES $tmp,
        FOREIGN KEY(p2) REFERENCES $tmp,
        FOREIGN KEY(winner) REFERENCES $tmp
    );";
    $conn->exec($sql);
    $_SESSION["comp"] = $comp;

    $sql = "INSERT INTO comps (id, name) VALUES ('$comp->id', '$comp->name')";
    $conn->exec($sql);

    header("Location: /setup/$comp->id");

} catch (PDOException $e) {
    echo $sql . "<br>" . $e->getMessage();
}

$conn = null;
