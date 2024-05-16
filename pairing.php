<?php

//TODO: Implement Glicko-2 algorithm and html generation functions
class pairing
{
    public string $id; //tournament id
    public item $p1; //item 1
    public item $p2; //item 2
    public string $player;//the player name
    public int $winner; //I think ID would be best here, but 1/2 could work as well. bool in that case

    //TODO: get a pairing from 1 of 3 options: random, rating based, and reliability.
    //for rating based, pair them against ones with close ratings, but still random
    //for reliability, have 1 be a very low reliability and have 2 be random. this will quickly stabilize things added mid comp
    //maybe split the pairing generation into a separate function, so we can
    function __construct(string $id, int $method)
    {
        $this->id = $id;
        $this->p1 = new item("");
        $this->p2 = new item("");
    }

    //TODO: generate sql to add the pairing to completed rounds
    function sql($id, $i1, $i2): string
    {
        throw new Error("sql not implemented");
    }
}


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
    function update(): string
{
        throw new Error("update not implemented");
}
}