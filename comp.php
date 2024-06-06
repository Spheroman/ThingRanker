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
public int $rankingoption;


static function fromName($name): comp
{
    $ret = new comp();
    $ret->name = $name;
    $ret->id = generateRandomString(6);
    $ret->started = false;
    $ret->publicadd = false;
    $ret->addwhilerun = false;
    $ret->playerlimit = 0;
    $ret->pairingtype = 0;
    $ret->maxrounds = 0;
    $ret->rankingoption = 0;
    return $ret;
}



}
