<?php

// Will only work with 1st dimension elements of the config file

// TODO: Make it work with multidimensional elements of the configuration

function config($name, $required = false)
{
    global $config;

    if (isset($config[$name]))
    {
        return $config[$name];
    }

    if ($required == true)
    {
        error('Missing config item');
    }
    else
    {
        return '';
    }
}

?>
