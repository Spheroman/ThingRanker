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