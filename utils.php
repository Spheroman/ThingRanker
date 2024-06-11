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
function generateTable(string $id, PDO $pdo, bool $rank = true): int
{
    try {
        // Query the competition entries
        if($rank)
            $stmt = $pdo->prepare("SELECT id, name, rating, variance FROM {$id} ORDER BY rating DESC, variance, name");
        else $stmt = $pdo->prepare("SELECT id, name, rating, variance FROM {$id} ORDER BY id DESC");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $count = 1;

        // Generate the table
        if($rank)
            echo '<tr><th>Rank</th><th>Name</th><th>Rating</th><th>Variance</th></tr>';
        else echo '<tr><th>Name</th><th>Rating</th><th>Variance</th><th>Delete</th></tr>';
        foreach ($data as $row) {
            echo '<tr>';
            if($rank)
                echo '<td>' . htmlspecialchars($count) . '</td>';
            echo '<td>' . htmlspecialchars($row['name']) . '</td>';
            echo '<td>' . htmlspecialchars($row['rating']) . '</td>';
            echo '<td>' . htmlspecialchars($row['variance']) . '</td>';
            if(!$rank)
                echo "<td>
<form action='/remove.php' method='POST'>
<input type='hidden' name='redirect' value='$id/setup'>
<input type='hidden' name='id' value=$id>
<input type='hidden' name='itemid' value={$row['id']}>
<button type='submit' style='height: 80px; width: 80px; font-size: 20pt'>✖</button>
</form>
</td>
                ";
            echo '</tr>';
            $count++;
        }
        return $count;
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return 0;
    }
}
