<?php

require "utils.php";

//TODO: improve ID generation function
//https://stackoverflow.com/a/16738409

//TODO: figure out pin hashing in SQL
//TODO: add vars for options and update comps table with columns for the vars
class comp
{
public string $name;
public string $id;
public bool $started;
public mixed $passcode;
public bool $publicadd;
public bool $addwhilerun;
public int $playerlimit;
public int $pairingtype;
public int $maxrounds;


static function fromName($name): comp
{
    $ret = new comp();
    $ret->name = $name;
    $ret->id = generateRandomString(6);
    return $ret;
}



}