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