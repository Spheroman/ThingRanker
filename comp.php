<?php
//TODO: improve ID generation function
//https://stackoverflow.com/a/16738409
function generateRandomString($length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

//TODO: figure out pin hashing in SQL
//TODO: add vars for options and update comps table with columns for the vars
class comp
{
    public string $name;
    public string $id;
    public bool $started;
    public int $rankingOption; // 0, 1, or 2 for different ranking methods
    public int $pairingOption; // 0, 1, or 2 for different pairing methods

    function __construct($name, $rankingOption = 0, $pairingOption = 0) {
        $this->name = $name;
        $this->id = generateRandomString(6);
        $this->started = false;
        $this->rankingOption = $rankingOption;
        $this->pairingOption = $pairingOption;
    }

    // Function to generate a competition table
    public function generateCompetitionTable($conn)
    {
        try {
            // Fetch competition data from the database
            $stmt = $conn->prepare("SELECT id, name, rating, confidence FROM {$this->id} ORDER BY rating DESC");
            $stmt->execute();
            $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

            // Generate the HTML table
            echo "<table>";
            echo "<tr><th>#</th><th>Name</th><th>Rating</th><th>Confidence</th></tr>";
            foreach ($items as $index => $item) {
                echo "<tr>";
                echo "<td>" . ($index + 1) . "</td>";
                echo "<td>" . htmlspecialchars($item['name']) . "</td>";
                echo "<td>" . $item['rating'] . "</td>";
                echo "<td>" . $item['confidence'] . "</td>";
                echo "</tr>";
            }
            echo "</table>";
        } catch (PDOException $e) {
            echo "SQL Error: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>
