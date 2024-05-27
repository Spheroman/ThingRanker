<?php
require "bayelo.php";
require "tablechecker.php";

//TODO: Implement Glicko-2 algorithm and html generation functions
class pairing
{
    public int $id;
    public Item $p1; //item 1
    public Item $p2; //item 2
    public string $player; //the player name
    public bool $winner; //did p1 win
    public string $tID; // ID of the tournament
    public bool $iscomplete; // Indicates if the pairing is complete

    //TODO: get a pairing from 1 of 3 options: random, rating based, and reliability.
    private function __construct(int $id, Item $p1, Item $p2, string $player, bool $winner, string $tID, bool $iscomplete)
    {
        $this->id = $id;
        $this->p1 = $p1;
        $this->p2 = $p2;
        $this->player = $player;
        $this->winner = $winner;
        $this->tID = $tID;
        $this->iscomplete = $iscomplete;
    }

    private static function fromArray($in): pairing
    {
        return new Pairing($in["id"], $in["p1"], $in["p2"], $in["player"], $in["winner"], $in["tID"], $in["iscomplete"]);
    }

    /**
     * @throws Exception
     */
    static function fromSQL(string $tID, int $id, PDO $pdo): pairing
    {
        if(!tableCheck($tID, $pdo))
            throw new Exception("id not found");
        $h2h = $tID . "_h2h";
        $conn = $pdo->prepare("SELECT id, p1, p2, winner, player, iscomplete FROM $h2h WHERE id=:id");
        $conn->bindParam(":id", $id, PDO::PARAM_INT);
        $conn->execute();
        $conn->setFetchMode(PDO::FETCH_ASSOC);
        $arr = $conn->fetch();
        $arr["p1"] = Item::fromSQL($tID,$arr["p1"], $pdo);
        $arr["p2"] = Item::fromSQL($tID,$arr["p2"], $pdo);
        $arr["tID"] = $tID;
        return Pairing::fromArray($arr);
    }

    /**
     * @throws Exception
     */
    static function fromRandom(string $tID, PDO $pdo): pairing
    {
        if(!tableCheck($tID, $pdo))
            throw new Exception("id not found");
        $conn = $pdo->prepare("SELECT * FROM $tID ORDER BY RAND() LIMIT 2");
        $conn->execute();
        $conn->setFetchMode(PDO::FETCH_ASSOC);

        $out["p1"] = $conn->fetchObject('Item');
        $out["p1"]->tID = $tID;
        $out["p2"] = $conn->fetchObject('Item');
        $out["p2"]->tID = $tID;
        $out["id"] = -1;
        $out["player"] = "";
        $out["winner"] = "";
        $out["tID"] = $tID;
        $out["iscomplete"] = false;
        $ret = Pairing::fromArray($out);
        $ret->insert($pdo);
        return $ret;
    }

    /**
     * @throws Exception
     */
    function setWinner(string $in): self
    {
        if ($this->p1->id == $in) {
            $this->winner = 1;
            return $this;
        }
        if ($this->p2->id == $in) {
            $this->winner = 0;
            return $this;
        }
        throw new Exception("winner not in pairing");
    }

    function calculate(PDO $pdo): self
    {
        $this->p1->update($pdo);
        $this->p2->update($pdo);
        update_ratings($this->p1, $this->p2, $this->winner);
        $this->p1->store($pdo);
        $this->p2->store($pdo);
        return $this;
    }

    function insert(PDO $pdo): void
    {
        $tableName = $this->tID . "_h2h";
        $insertSql = "INSERT INTO $tableName (p1, p2, player, winner) 
VALUES (:item1_id,  :item2_id, :player, :winner);
   ";
        $stmt = $pdo->prepare($insertSql);
        $stmt->bindParam(':item1_id', $this->p1->id, PDO::PARAM_INT);
        $stmt->bindParam(':item2_id', $this->p2->id, PDO::PARAM_INT);
        $stmt->bindParam(':player', $this->player, PDO::PARAM_STR);
        $stmt->bindParam(':winner', $this->winner, PDO::PARAM_BOOL);
        $stmt->execute();
        $this->id = $pdo->lastInsertId();

    }

    function update(PDO $pdo): void
    {
        $tableName = $this->tID . "_h2h";
        $insertSql = "UPDATE $tableName
SET (p1 = :item1_id, p2 = :item2_id, player = :player, winner = :winner, iscomplete = :iscomplete) 
WHERE id=:pid
";
        $stmt = $pdo->prepare($insertSql);
        $stmt->bindParam(':item1_id', $this->p1->id, PDO::PARAM_INT);
        $stmt->bindParam(':item2_id', $this->p2->id, PDO::PARAM_INT);
        $stmt->bindParam(':player', $this->player, PDO::PARAM_STR);
        $stmt->bindParam(':winner', $this->winner, PDO::PARAM_BOOL);
        $stmt->bindParam(':iscomplete', $this->iscomplete, PDO::PARAM_BOOL);
        $stmt->bindParam(':pid', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
