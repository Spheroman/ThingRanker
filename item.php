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
    //sql injection is still an issue
    function update(): string
    {
        $item_update ="UPDATE items
                                SET name = '{$this->name}',
                                    rating = '{$this->rating}',
                                    confidence = '{$this->confidence}'
                                WHERE id = '{$this->id}';
                            ";
        return $item_update;
    }
}
