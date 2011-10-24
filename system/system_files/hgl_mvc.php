<?php

// Include all libraries

// Required libraries
require_once(SYSTEM_FILES_PATH.'/config.php');
if (file_exists(SYSTEM_PATH.'/config.php')) {
    require_once(SYSTEM_PATH.'/config.php');
}
if (file_exists(SYSTEM_PATH.'/routes.php')) {
    require_once(SYSTEM_PATH.'/routes.php');
}
require_once(SYSTEM_FILES_PATH.'/config_handler.php');
require_once(SYSTEM_FILES_PATH.'/hgl_exception.php');
require_once(SYSTEM_FILES_PATH.'/error_handler.php');
require_once(SYSTEM_FILES_PATH.'/security.php');
require_once(SYSTEM_FILES_PATH.'/mvc_handler.php');
require_once(SYSTEM_FILES_PATH.'/base_model.php');
require_once(SYSTEM_FILES_PATH.'/uri.php');
require_once(SYSTEM_FILES_PATH.'/form_handler.php');
require_once(SYSTEM_FILES_PATH.'/file_handler.php');
require_once(SYSTEM_FILES_PATH.'/validation.php');
require_once(SYSTEM_FILES_PATH.'/output_buffering.php');


// Optional libraries (can be commented out with no impact to the HGL core)
require_once(SYSTEM_FILES_PATH.'/database/database.php');
require_once(SYSTEM_FILES_PATH.'/database/database_interface.php');
require_once(SYSTEM_FILES_PATH.'/csv_handler.php');
require_once(SYSTEM_FILES_PATH.'/pagination.php');
require_once(SYSTEM_FILES_PATH.'/file_model.php');
require_once(SYSTEM_FILES_PATH.'/language_handler.php');
require_once(SYSTEM_FILES_PATH.'/phpmailer/class.phpmailer.php');

try
{
    // Load all user created libraries
    load_files_in_dir(SYSTEM_PATH.'/libraries/');

    // Load all user created models
    load_files_in_dir(SYSTEM_PATH.'/models/');


    // Start the session so it can be used throughout the application
    if (config('sessions_enabled') == true)
    {
        session_start();
    }

    // After the mod_rewrite we might lose the $_GET variables
    // Here we reset them so we can use them normally, only if they aren't set
    $uri = get_uri_with_query_string();
    $pos = strpos($uri, '?');
    if ($pos)
    {
        $get_string = substr($uri, $pos+1);
        $get_parts = explode('&', $get_string);
        foreach ($get_parts as $get_var) {
            $get_var_parts = explode('=', $get_var);
            if (isset($get_var_parts[1])) {
                $value = urldecode($get_var_parts[1]);
            } else {
                $value = '';
            }
            if (!isset($_GET[$get_var_parts[0]]))
            {
                $_GET[$get_var_parts[0]] = $value;
                $_REQUEST[$get_var_parts[0]] = $value;
            }
        }
    }

    // For security purposes, we sanitize all data that has been sent to the page (GET or POST)
    // In the configuration, we automatically always filter out XSS (security_data_filters setting)
    if (isset($_GET))
    {
        $_GET = sanitize_data($_GET);
    }
    if (isset($_POST))
    {
        $_POST = sanitize_data($_POST);
    }


    // Get the necessary information necessary to run the called page
    $controller_name = get_routed_controller_name();
    $function_name = get_function_name();
    $controller = load_controller($controller_name);

    // Use output buffering to get all the page's HTML
    // This gives us the option to not optionally not display the HTML on the page if an error occurs (just show the error)
    if (output_buffering() == true)
    {
        if (!ob_start())
        {
            error('Failed to start output buffering. Please disable it from your config.php file');
        }
    }
    execute_function($controller, $function_name);
    if (output_buffering() == true)
    {
        $html = ob_get_clean();

        // Parse the HTML code and change the links if multilingual is enabled in config file
        //
        // TODO: Do better XML parsing to replace the links, using DomDocument breaks the validity of XHTML
        //
        //$html = language_configuration($html);

        // After any potential parsing, display the final HTML of the page
        echo $html;
    }
}
catch (hgl_exception $e)
{
    // If there is an exception handler, call that to try and handle the exception
    // Example declaration of an exception handler:

    // function handle_exception($exception) {}
    // $config['exception_handler'] = 'handle_exception';

    if (config('exception_handler'))
    {
        // Wrap the exception handler call in a try catch block, just incase it causes one itself and then we just end execution by printing the exception out
        try
        {
            // If the exception handler can't handle the exception, it should just re-throw it
            $handler = config('exception_handler');
            $handler($e);
        }
        catch (hgl_exception $e)
        {
            echo $e;
        }

    }
    else
    {
        echo $e;
    }
}

?>