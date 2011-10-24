<?php

interface database_interface
{

    function connect($host, $username, $password, $database, $port = 0);
    function query($sql, $args = null);
    function close();

    function escape($value);
    function unescape($value);

    function insert_id();

}

?>