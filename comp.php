<?php

require "utils.php";

//TODO: improve ID generation function
//https://stackoverflow.com/a/16738409

//TODO: figure out pin hashing in SQL
//TODO: add vars for options and update comps table with columns for the vars
class comp
{
    public string $name;
    public string $id;
    public bool $started;
    public mixed $passcode;
    public bool $publicadd;
    public bool $addwhilerun;
    public int $playerlimit;
    public int $pairingtype;
    public int $maxrounds;


    static function fromName($name): comp
    {
        $ret = new comp();
        $ret->name = $name;
        $ret->id = generateRandomString(6);
        return $ret;
    }

    // Function to generate table dynamically
    public function generateTable(PDO $pdo)
    {
        try {
            // Check if the competition table exists
            if (!tableCheck($this->id, $pdo)) {
                echo "Competition table does not exist.";
                return;
            }

            // Query the competition entries
            $stmt = $pdo->prepare("SELECT id, name, rating, confidence FROM {$this->id} ORDER BY rating DESC");
            $stmt->execute();
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Generate the table
            echo '<table border="1">';
            echo '<tr><th>ID</th><th>Name</th><th>Rating</th><th>Confidence</th></tr>';
            foreach ($data as $row) {
                echo '<tr>';
                echo '<td>' . htmlspecialchars($row['id']) . '</td>';
                echo '<td>' . htmlspecialchars($row['name']) . '</td>';
                echo '<td>' . htmlspecialchars($row['rating']) . '</td>';
                echo '<td>' . htmlspecialchars($row['confidence']) . '</td>';
                echo '</tr>';
            }
            echo '</table>';
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>