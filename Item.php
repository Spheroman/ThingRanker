
<?php

class Item
{
    public string $tID;

    public int $id;
    public string $name;
    public int $rating;
    public int $variance;

    //TODO: create constructor from PDO output
    function __construct(array $in, $tID)
    {
        $this->tID = $tID;
        $this->id = $in['id'] ?? 0;
        $this->name = $in['name'] ?? '';
        $this->rating = $in['rating'] ?? 0;
        $this->variance = $in['confidence'] ?? 0;
    }

    /**
     * @throws Exception
     */
    static function fromSQL($tID, $id, $pdo): Item
    {
        if(!tableCheck($tID, $pdo))
            throw new Exception("id not found");
        $conn = $pdo->prepare("SELECT id, name, rating, confidence FROM $tID WHERE id=:id");
        $conn->bindParam(":id", $id, PDO::PARAM_INT);
        $conn->execute();
        $conn->setFetchMode(PDO::FETCH_ASSOC);
        $arr = $conn->fetchAll();
        return new Item($arr, $tID);
    }


    function update(PDO $pdo): self
    {
        try{
        $stmt = $pdo->prepare("SELECT name, rating, confidence FROM :tID WHERE id=:id");
        $stmt->bindParam(":tID", $this->tID);
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $arr = $stmt->fetchAll();
        $this->name = htmlspecialchars($arr['name']);
        $this->rating = $arr['rating'];
        $this->variance = $arr['confidence'];
        } catch (PDOException $e) {
        echo "SQL Error: " . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8');
        }
        return $this;
    }

    function store(PDO $pdo): void
    {
        try{/** @noinspection SqlResolve */
        $stmt = $pdo->prepare("UPDATE :tID 
                                  SET name = :name, 
                                      rating = :rating, 
                                      confidence = :confidence 
                                  WHERE id = :id");
        $stmt->bindParam(':tID', $this->id, PDO::PARAM_STR);
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':rating', $this->rating, PDO::PARAM_INT);
        $stmt->bindParam(':confidence', $this->variance, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        } catch (PDOException $e) {
        echo "SQL Error: " . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8');
        }
    }
}
