<?php

function output_buffering()
{
    if (config('output_buffering') == true && strtolower(ini_get('output_buffering')) != 'off' && ini_get('output_buffering') != '0')
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>