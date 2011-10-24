<?php

function get_controller_name()
{
    global $config;
    global $routes;

    $uri_parts = get_uri_parts();

    if (config('multilingual') == false && isset($uri_parts[0]) && strlen(trim($uri_parts[0])) > 0)
    {
        $controller_name = $uri_parts[0];
    }
    else if (config('multilingual') == true && isset($uri_parts[1]) && strlen(trim($uri_parts[1])) > 0)
    {
        $controller_name = $uri_parts[1];
    }
    else
    {
        $controller_name = config('default_controller', true);
    }
    
    return $controller_name;
}

function get_routed_controller_name() {
    global $routes;
    
    $controller_name = get_controller_name();
    
    if (isset($routes) && array_key_exists($controller_name, $routes)) {
        $parts = explode('/', $routes[$controller_name]);
        return $parts[0];
    }
    
    return $controller_name;
}

function get_function_name()
{
    global $config;
    global $routes;

    $uri_parts = get_uri_parts();

    if (config('multilingual') == false && isset($uri_parts[1]) && $uri_parts[1] != '')
    {
        $function_name = $uri_parts[1];
    }
    else if (config('multilingual') == true && isset($uri_parts[2]) && $uri_parts[2] != '')
    {
        $function_name = $uri_parts[2];
    }
    else
    {
        $function_name = config('default_controller_function', true);
    }

    $controller_name = get_controller_name();
    if (isset($routes) && array_key_exists($controller_name, $routes)) {
        $parts = explode('/', $routes[$controller_name]);
        return $parts[1];
    }
    
    return $function_name;
}

function load_view($view_name = null, $args = null, $return_output = false)
{
    try
    {
        $view_name = str_replace('.', '', $view_name);

        $view_filename = SYSTEM_PATH.'/views/'.$view_name.'.php';
        if (!is_null($view_name) && $view_name != '' && file_exists($view_filename) == true)
        {
            if (is_array($args))
            {
                extract($args);
            }
            if ($return_output == true) {
                ob_start();
            }
            require $view_filename;
            if ($return_output == true) {
                return ob_get_clean();
            }
        }
        else
        {
            error('Invalid view');
        }
    }
    catch (hgl_exception $e)
    {
        throw $e;
    }
}


function load_controller($controller_name)
{
    try
    {
        $controller_name = str_replace('.', '', $controller_name);

        $controller_file = SYSTEM_PATH.'/controllers/'.$controller_name.'.php';
        if (file_exists($controller_file) == true)
        {
            require_once $controller_file;

            if (class_exists($controller_name))
            {
                return new $controller_name();
            }
            else
            {
                error('Controller could not be loaded ('.$controller_name.')');
            }
        }
        else
        {
            error('Controller could not be found ('.$controller_name.')');
        }
    }
    catch (hgl_exception $e)
    {
        throw $e;
    }
}

function execute_function($controller, $function_name)
{
    try
    {
        if (method_exists($controller, $function_name))
        {
            $uri_parts = get_uri_parts();

            call_user_func_array(array($controller, $function_name), array_slice($uri_parts, 2));
        }
        else
        {
            error('Invalid function ('.$function_name.')');
        }
    }
    catch (hgl_exception $e)
    {
        throw $e;
    }
}

?>