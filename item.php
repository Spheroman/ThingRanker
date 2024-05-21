<?php

class item
{
    public int $id;
    public string $name;
    public int $rating;
    public int $confidence;

    //TODO: create constructor from PDO output
    function __construct($in)
    {
        throw new Error("item constructor not implemented");
    }

    //TODO: generate sql to update the item in the database
    function update(PDO $pdo): string
    {
        $stmt = $pdo->prepare("UPDATE items 
                                  SET name = :name, 
                                      rating = :rating, 
                                      confidence = :confidence 
                                  WHERE id = :id");

        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':rating', $this->rating, PDO::PARAM_INT);
        $stmt->bindParam(':confidence', $this->confidence, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        $queryString = $stmt->queryString;

        return $queryString;
    }
}
