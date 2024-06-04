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

function __construct($name) {
    $this->name = $name;
    $this->id = generateRandomString(6);
  }
}