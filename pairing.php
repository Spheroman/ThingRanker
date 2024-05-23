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

    //TODO: get a pairing from 1 of 3 options: random, rating based, and reliability.
    function __construct(string $id, int $method)
    {
        $this->id = $id;
        $this->p1 = new item([], $this->id);
        $this->p2 = new item([], $this->id);
        $this->player = "";
        $this->winner = 0;
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
    function sql(PDO $pdo, string $id, item $i1, item $i2): string
    {
        $insertSql = "INSERT INTO completed_rounds (tournament_id, item1_id, item2_id, player, winner) 
                      VALUES (:tournament_id, :item1_id, :item2_id, :player, :winner);";
        $insertParams = [
            ':tournament_id' => $id,
            ':item1_id' => $i1->id,
            ':item2_id' => $i2->id,
            ':player' => $this->player,
            ':winner' => $this->winner
        ];

        $updateSql1 = $i1->getUpdateSql();
        $updateSql2 = $i2->getUpdateSql();

        $combinedSql = $insertSql . " " . $updateSql1['sql'] . " " . $updateSql2['sql'];

        $combinedParams = array_merge($insertParams, $updateSql1['params'], $updateSql2['params']);

        $stmt = $pdo->prepare($combinedSql);
        $stmt->execute($combinedParams);

        return $combinedSql;
    }
}
