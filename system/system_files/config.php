<?php

$config = array();

// MVC configuration
$config['default_controller'] = 'home';
$config['default_controller_function'] = 'index';

// If installed on a subdirectory, specify it here (needed to adjust the links)
// Also look at the wiki page here: http://code.google.com/p/happygolucky/wiki/InstallingInSubDirectory
$config['sub_directory'] = null;


$config['output_buffering'] = true;

// Security
$config['security_data_filters'] = array('xss');

// Database configuration
$config['database']['default']['type'] = 'mysql'; // mysql or mysqli
$config['database']['default']['host'] = 'localhost';
$config['database']['default']['username'] = 'username';
$config['database']['default']['password'] = 'password';
$config['database']['default']['database'] = 'database';

// Languages configuration
$config['multilingual'] = false;
$config['default_language'] = 'en';
//$config['auto_convert_links'] = true; // Doesn't work yet

// File uploads
$config['safe_file_upload'] = false;
$config['upload_folder'] = BASE_PATH.'/uploads';
$config['safe_upload_folder'] = BASE_PATH.'/../uploads';
$config['overwrite_existing'] = false;


// Sessions
$config['sessions_enabled'] = true;

// Application configuration
$config['contact_form_address'] = "you@example.com";

$config['currency'] = '$';
$config['payment_method'] = 'paypal';

?>