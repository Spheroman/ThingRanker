<?php
require "item.php";

//TODO: Implement Glicko-2 algorithm and html generation functions
class pairing
{
    public string $id; //tournament id
    public item $p1; //item 1
    public item $p2; //item 2
    public string $player; //the player name
    public int $winner; //I think ID would be best here, but 1/2 could work as well. bool in that case

    //TODO: get a pairing from 1 of 3 options: random, rating based, and reliability.
    function __construct(string $id, int $method)
    {
        $this->id = $id;
        $this->p1 = new item("");
        $this->p2 = new item("");
        $this->player = "";
        $this->winner = 0;
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
