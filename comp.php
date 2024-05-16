<?php

//TODO: improve ID generation function
//https://stackoverflow.com/a/16738409
function generateRandomString($length = 10): string
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}

//TODO: figure out pin hashing in SQL
//TODO: add vars for options and update comps table with columns for the vars
class comp
{
public string $name;
public string $id;
public bool $started;

function __construct($name) {
    $this->name = $name;
    $this->id = generateRandomString(6);
  }
}