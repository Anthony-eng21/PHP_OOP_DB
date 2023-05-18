<?php

function db_connect()
{
    //all lower case instance / global creds lol
    $connection = new mysqli(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
    confirm_db_connect($connection);
    return $connection;
}

//error helper cb
function confirm_db_connect($connection)
{
    //check to see if there is a connection errno
    //refering properties of this object instead of calling procedural built in functions
    if ($connection->connect_errno) {
        $msg = "Database connection failed: ";
        $msg .= $connection->connect_error;
        $msg .= " (" . $connection->connect_errorno . ")";
        exit($msg); //output msg
    }
}

function db_disconnect($connection)
{
    if (isset($connection)) {
        $connection->close();
    }
}

?>