<?php

function get_uri()
{
    $uri = trim($_SERVER['REQUEST_URI'], ' /');

    if ( config('sub_directory') )
    {
        $subdir = ltrim(config('sub_directory'), '/');
        $subdir = rtrim($subdir, '/');
        $uri_after = substr($uri, 0, strlen($subdir));
        if ($subdir == $uri_after)
        {
            $uri = substr($uri, strlen($subdir)+1 );
        }
    }

    $query_string = strpos($uri, '?');
    if ($query_string)
    {
        $uri = substr($uri, 0, $query_string);
    }
    return $uri;
}

function is_redirected_uri()
{
    if (isset($_SERVER['REDIRECT_QUERY_STRING']) && isset($_SERVER['REDIRECT_UNIQUE_ID']) && isset($_SERVER['REDIRECT_URL']))
    {
        return true;
    }
    else
    {
        return false;
    }
}

function get_uri_with_query_string()
{
    $uri = trim($_SERVER['REQUEST_URI'], ' /');

    if ( config('sub_directory') )
    {
        $subdir = ltrim(config('sub_directory'), '/');
        $subdir = rtrim($subdir, '/');
        $uri_after = substr($uri, 0, strlen($subdir));
        if ($subdir == $uri_after)
        {
            $uri = substr($uri, strlen($subdir)+1 );
        }
    }

    return $uri;
}

function get_uri_parts()
{
    $uri = get_uri();
    if (substr($uri, 0, 1) != '?')
    {
        $uri_parts = explode("/", $uri);
    }
    else
    {
        $uri_parts = array();
    }
    return $uri_parts;
}

function redirect($url)
{
    header("Location: ".$url);
    exit();
}

?>