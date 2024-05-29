<?php
require_once "table.php"; // Include table.php to access the generateCompetitionTable function

// TODO: improve ID generation function
// https://stackoverflow.com/a/16738409
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

// TODO: figure out pin hashing in SQL
// TODO: add vars for options and update comps table with columns for the vars
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

    // Function to fetch competition data
    public function fetchCompetitionData($conn)
    {
        try {
            $stmt = $conn->prepare("SELECT id, name, rating, confidence FROM {$this->id} ORDER BY rating DESC");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            throw new Exception("SQL Error: " . $e->getMessage());
        } catch (Exception $e) {
            throw new Exception("Error: " . $e->getMessage());
        }
    }
}

// Function to create a database connection
function createDatabaseConnection(): PDO
{
    $host = ''; // database host
    $db = ''; // database name
    $user = ''; // database user
    $pass = ''; // database password
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    try {
        return new PDO($dsn, $user, $pass, $options);
    } catch (PDOException $e) {
        throw new PDOException($e->getMessage(), (int)$e->getCode());
    }
    $id = "competition_id"; // Replace "competition_id" with the actual competition ID
    generateCompetitionTable($conn, $id);
}
?>
