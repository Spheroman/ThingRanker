<?php

function tableCheck(string $id, PDO $pdo): bool
{
    try{
    $conn = $pdo->prepare("SELECT COUNT(*) FROM comps WHERE id = :id");
    $conn->bindParam(":id", $id, PDO::PARAM_STR);
    $conn->execute();
    return (bool)$conn->fetchColumn();
    } catch (PDOException $e){
        return false;
    }
}

function startedCheck(string $id, PDO $pdo): bool
{
    try{
        $conn = $pdo->prepare("SELECT started FROM comps WHERE id = :id");
        $conn->bindParam(":id", $id, PDO::PARAM_STR);
        $conn->execute();
        return (bool)$conn->fetchColumn();
    } catch (PDOException $e){
        return false;
    }
}

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

// Function to generate table dynamically
function generateTable(PDO $pdo)
{
    try {
        // Check if the competition table exists
        if (!tableCheck($this->id, $pdo)) {
            echo "Competition table does not exist.";
            return;
        }

        // Query the competition entries
        $stmt = $pdo->prepare("SELECT id, name, rating, variance FROM {$this->id} ORDER BY rating DESC");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Generate the table
        echo '<table border="1">';
        echo '<tr><th>ID</th><th>Name</th><th>Rating</th><th>Variance</th></tr>';
        foreach ($data as $row) {
            echo '<tr>';
            echo '<td>' . htmlspecialchars($row['id']) . '</td>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['rating']) . '</td>';
            echo '<td>' . htmlspecialchars($row['variance']) . '</td>';
            echo '</tr>';
        }
        echo '</table>';
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
