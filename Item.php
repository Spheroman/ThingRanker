
<?php

class Item
{
    public string $tID = "";

    public int $id;
    public string $name;
    public int $rating;
    public int $confidence;

    //TODO: create constructor from PDO output
    /*
    function __construct(, $tID)
    {
        $this->tID = $tID;
        $this->id = $in['id'] ?? 0;
        $this->name = $in['name'] ?? '';
        $this->rating = $in['rating'] ?? 0;
        $this->variance = $in['confidence'] ?? 0;
    }
*/

    function __toString()
    {
        return $this->name;
    }

    /**
     * @throws Exception
     */
    static function fromSQL($tID, $id, $pdo): Item
    {
        if(!tableCheck($tID, $pdo))
            throw new Exception("tid not found");
        $conn = $pdo->prepare("SELECT id, name, rating, confidence FROM $tID WHERE id=:id");
        $conn->bindParam(":id", $id, PDO::PARAM_INT);
        $conn->execute();
        $conn->setFetchMode(PDO::FETCH_ASSOC);
        $ret = $conn->fetchObject('Item');
        $ret->tID = $tID;
        return $ret;
    }


    function update(PDO $pdo): self
    {
        try{
        $stmt = $pdo->prepare("SELECT name, rating, confidence FROM $this->tID WHERE id=:id");
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $arr = $stmt->fetch();
        $this->name = htmlspecialchars($arr['name']);
        $this->rating = $arr['rating'];
        $this->confidence = $arr['confidence'];
        } catch (PDOException $e) {
        echo "SQL Error: " . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8');
        }
        return $this;
    }

    function store(PDO $pdo): void
    {
        try{/** @noinspection SqlResolve */
        $stmt = $pdo->prepare("UPDATE $this->tID
                                  SET name = :name, 
                                      rating = :rating, 
                                      confidence = :confidence 
                                  WHERE id = :id");
        $stmt->bindParam(':name', $this->name, PDO::PARAM_STR);
        $stmt->bindParam(':rating', $this->rating, PDO::PARAM_INT);
        $stmt->bindParam(':confidence', $this->confidence, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        } catch (PDOException $e) {
        echo "SQL Error: " . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8');
        }
    }
}
