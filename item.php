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
    function update(): string
{
        throw new Error("update not implemented");
}
}