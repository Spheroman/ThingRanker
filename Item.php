
<?php

class Item
{
    public string $tID = "";
    public int $id;
    public string $name;
    public int $rating;
    public int $variance;

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
        $conn = $pdo->prepare("SELECT id, name, rating, variance FROM $tID WHERE id=:id");
        $conn->bindParam(":id", $id, PDO::PARAM_INT);
        $conn->execute();
        $conn->setFetchMode(PDO::FETCH_ASSOC);
        $ret = $conn->fetchObject('Item');
        $ret->name = htmlspecialchars($ret->name);
        $ret->tID = $tID;
        return $ret;
    }


    function update(PDO $pdo): self
    {
        try{
        $stmt = $pdo->prepare("SELECT name, rating, variance FROM $this->tID WHERE id=:id");
        $stmt->bindParam(":id", $this->id);
        $stmt->execute();
        $stmt->setFetchMode(PDO::FETCH_ASSOC);
        $arr = $stmt->fetch();
        $this->rating = $arr['rating'];
        $this->variance = $arr['variance'];
        } catch (PDOException $e) {
        echo "SQL Error: " . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8');
        }
        return $this;
    }

    function store(PDO $pdo): void
    {
        try{/** @noinspection SqlResolve */
        $stmt = $pdo->prepare("UPDATE $this->tID
                                  SET rating = :rating, 
                                      variance = :variance
                                  WHERE id = :id");
        $stmt->bindParam(':rating', $this->rating, PDO::PARAM_INT);
        $stmt->bindParam(':variance', $this->variance, PDO::PARAM_INT);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        } catch (PDOException $e) {
        echo "SQL Error: " . htmlspecialchars($e->getMessage(), ENT_NOQUOTES, 'UTF-8');
        }
    }
}
