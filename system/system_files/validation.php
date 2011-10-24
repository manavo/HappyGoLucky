<?php

function required($name)
{
    if (!isset($_POST[$name]) || !$_POST[$name])
    {
        throw new hgl_exception('The '.$name.' is required');
    }
}

function valid_email($name)
{
    if (isset($_POST[$name]) || $_POST[$name])
    {
        $address = $_POST[$name];

        $regex = "/[A-Z0-9._%+-]+@(?:[A-Z0-9-]+\.)+[A-Z]{2,4}/i";
        preg_match($regex, $address, $matches);

        if (count($matches) == 0)
        {
            throw new hgl_exception('The email address is invalid');
        }
    }
}

function numeric($name)
{
    if (isset($_POST[$name]) || $_POST[$name])
    {
        $value = $_POST[$name];

        if (is_numeric($value) == false)
        {
            throw new hgl_exception('The '.$name.' field has to be numeric');
        }
    }
}

function min_length($name, $length) {
    if (strlen($_POST[$name]) < $length) {
        throw new hgl_exception('The '.$name.' needs to be at least '.$length.' characters');
    }
}

function max_length($name, $length) {
    if (strlen($_POST[$name]) > $length) {
        throw new hgl_exception('The '.$name.' needs to be less than '.$length.' characters');
    }
}

function matches($name, $other_name) {
    if (isset($_POST[$name]) && isset($_POST[$other_name]) && $_POST[$name] == $_POST[$other_name]) {
        return true;
    } else {
        throw new hgl_exception('The '.$other_name.' needs to match the '.$name);
    }
}

// Doesn't work yet
function integer($name)
{
    if (isset($_POST[$name]) || $_POST[$name])
    {
        $value = $_POST[$name];

        if (is_integer($value) == false)
        {
            throw new hgl_exception('The '.$name.' field has to be an integer');
        }
    }
}

?>
