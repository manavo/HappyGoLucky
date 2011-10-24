<?php

// Error reporting should be turned off if a site is going to go live (E_NONE)
error_reporting(E_ALL);

// Define a few constants to be easily used throughout the application
// Used for including files mainly (and uploading)
define('BASE_PATH', dirname(__FILE__));
define('SYSTEM_PATH', BASE_PATH.'/system');
define('SYSTEM_FILES_PATH', SYSTEM_PATH.'/system_files');

// The main file which initializes the MVC (includes all other necessary libraries)
$system_file = SYSTEM_FILES_PATH.'/hgl_mvc.php';

// To ensure that execution is terminated gracefully in case of failure,
// we check that the file exists before including it
if (file_exists($system_file) == true)
{
    require_once $system_file;
}
else
{
    die('Could not start the MVC framework. Please check your installation and configuration.');
}

?>