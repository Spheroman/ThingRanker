<?php

//TODO: add $_POST["name"] to item table, and update the date in the comps table
//TODO: sql injection protection

header("Location: /". $_POST["redirect"] . "/" . $_POST["id"]); //redirect to /setup page