<?php

class item
{
    public int $id;
    public string $name;
    public int $rating;
    public int $confidence;

    //TODO: create constructor from PDO output
    function __construct(array $in)
    {
        $this->id = $in['id'] ?? 0;
        $this->name = $in['name'] ?? '';
        $this->rating = $in['rating'} ?? 0;
        $this->confidence = $in['confidence'] ?? 0;
    }

    //TODO: generate sql to update the item in the database
    function update(): string
{
        throw new Error("update not implemented");
}
}
