<?php

class file
{

    function __construct($filename)
    {
        
    }

    function upload($upload_config = array())
    {
        global $config;

        if (!is_array($upload_config))
        {
            $upload_config = array();
        }

        if (isset($upload_config['upload_dir']))
        {
            $upload_dir = $upload_config['upload_dir'];
        }
        else
        {
            if (isset($upload_config['upload_folder_safe']))
            {
                $safe_upload = $upload_config['upload_folder_safe'];
            }
            else
            {
                if (isset($config['safe_file_upload']))
                {
                    $safe_upload = $config['safe_file_upload'];
                }
                else
                {
                    $safe_upload = false;
                }
            }

            if ($safe_upload == true && isset($config['upload_folder_safe']))
            {
                $upload_dir = $config['upload_folder_safe'];
            }
            else
            {
                if (isset($config['upload_folder']))
                {
                    $upload_dir = $config['upload_folder'];
                }
                else
                {
                    error('No upload folder specified');
                }
            }
        }

        if (isset($upload_config['overwrite']))
        {
            $overwrite = $upload_config['overwrite'];
        }
        else
        {
            if (isset($config['overwrite_existing']))
            {
                $overwrite = $config['overwrite_existing'];
            }
            else
            {
                $overwrite = false;
            }
        }

        if (isset($upload_config['unique']))
        {
            $unique = $upload_config['unique'];
        }
        else
        {
            if (isset($config['unique_filename']))
            {
                $unique = $config['unique_filename'];
            }
            else
            {
                $unique = true;
            }
        }

        if (is_dir($upload_dir) == false)
        {
            error('Invalid upload directory');
        }
        
        $file_perms = substr(sprintf('%o', fileperms('/tmp')), -3);
        echo $file_perms;
        if ($file_perms != '777')
        {
            error('Invalid permissions on upload dir. Must have 777, but now has '.$file_perms);
        }

        if (substr($upload_dir, -1, 1) != '/')
        {
            $upload_dir .= '/';
        }

        if (isset($name) && !is_null($name) && $name && isset($_FILES[$name]))
        {
            $files = array($_FILES[$name]);
        }
        else
        {
            $files = $_FILES;
        }

        foreach ($files as $file)
        {
            if (!$file['error'])
            {
                print_r($file);
                $filename_parts = explode('.', $file['name']);
                $extension = $filename_parts[(count($filename_parts)-1)];
                $done = false;

                while ($done == false)
                {
                    if ($unique == true)
                    {
                        $filename = $upload_dir.md5($file['name'].time()).'.'.$extension;
                    }
                    else
                    {
                        $filename = $upload_dir.$file['name'];
                    }
                    
                    if (file_exists($filename) == false || $overwrite == true)
                    {
                        if (file_exists($filename) == true)
                        {
                            unlink($filename);
                        }
                        
                        if (move_uploaded_file($file['tmp_name'], $filename) == true)
                        {
                            $done = true;
                        }
                        else
                        {
                            $done = false;
                        }
                    }
                }
            }
        }
    }


}

?>
