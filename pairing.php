<?php
require "item.php";

//TODO: Implement Glicko-2 algorithm and html generation functions
class pairing
{
    public string $id; //tournament id
    public item $p1; //item 1
    public item $p2; //item 2
    public string $player; //the player name
    public bool $winner; //did p1 win
    public int $pairing_id; // ID of the pairing
    public bool $iscomplete; // Indicates if the pairing is complete

    //TODO: get a pairing from 1 of 3 options: random, rating based, and reliability.
    function __construct(string $id, item $p1, item $p2, string $player, bool $winner)
    {
        $this->id = $id;
        $this->p1 = $p1;
        $this->p2 = $p2;
        $this->player = $player;
        $this->winner = $winner;
    }

    static function fromArray(array $in): pairing
    {
        return new Pairing($in["id"], $in["p1"], $in["p2"], $in["player"], $in['winner']);
    }

    static function fromSQL(string $tID, int $id, PDO $pdo): pairing
    {
        $conn = $pdo->prepare("SELECT p1, p2, winner, player, iscomplete FROM :table_h2h WHERE id=:id");
        $tID = $tID."_h2h";
        $conn->bindParam(":table", $tID, PDO::PARAM_STR);
        $conn->bindParam(":id", $id, PDO::PARAM_INT);
        $conn->execute();
        $conn->setFetchMode(PDO::FETCH_ASSOC);
        $arr = $conn->fetchAll();
        return Pairing::fromArray($arr);
    }

    /**
     * @throws Exception
     */
    function setWinner(string $in): self
    {
        if($this->p1->id==$in){
            $this->winner = 1;
            return $this;
        }
        if($this->p2->id==$in){
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

    //TODO: generate sql to add the pairing to completed rounds
    function sql(PDO $pdo): void
    {
        $tableName = $this->id . "_h2h";

        $insertSql = "INSERT INTO $tableName (tournament_id, item1_id, item2_id, player, winner) 
                      VALUES (:tournament_id, :item1_id, :item2_id, :player, :winner, :pairing_id, :iscomplete);";
        $insertParams = [
            ':tournament_id' => $this->id,
            ':item1_id' => $this->p1->id,
            ':item2_id' => $this->p2->id,
            ':player' => $this->player,
            ':winner' => $this->winner ? 1 : 0,
            ':pairing_id' => $this->pairing_id,
            ':iscomplete' => $this->iscomplete
        ];

        $stmt = $pdo->prepare($insertSql);
        $stmt->execute($insertParams);
    }
}
