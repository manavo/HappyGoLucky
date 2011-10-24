<?php

function load_files_in_dir($directory)
{
    if (is_dir($directory) == true)
    {
        $handle = opendir($directory);
        if ($handle) {
            while (false !== ($file = readdir($handle))) {
                if ($file != "." && $file != ".." && is_file($directory.$file) == true) {
                    if (substr($file, -4) == '.php') {
                        require_once $directory.$file;
                    }
                }
            }
            closedir($handle);
        }
        else
        {
            error('Could not read directory');
        }
    }
}

?>