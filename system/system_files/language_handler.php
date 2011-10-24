<?php

$language_text = array();

function get_language()
{
    global $config;

    if ($config['multilingual'] == true)
    {
        $uri_parts = get_uri_parts();

        if (isset($uri_parts[0]) && $uri_parts[0])
        {
            return $uri_parts[0];
        }
        else
        {
            return $config['default_language'];
        }
    }
    else
    {
        return null;
    }
}

function lang($name)
{
    global $language_text;
    
    if (isset($language_text[$name]))
    {
        return $language_text[$name];
    }

    return '';
}

function load_language_file($file)
{
    global $language_text;
    global $config;

    if ($config['multilingual'] == true)
    {
        $filepath = SYSTEM_PATH.'/languages/'.get_language().'/'.$file.'.php';

        if (file_exists($filepath) == true)
        {
            require_once $filepath;

            foreach ($lang as $key => $value)
            {
                $language_text[$key] = $value;
            }
        }
        else
        {
            error("Couldn't find language file");
        }
    }
}

function language_configuration($html)
{
    global $config;
    
    if ($config['multilingual'] == true && $config['auto_convert_links'] == true)
    {
        $dom = new domDocument();

        $dom->preserveWhiteSpace = true;
        $dom->strictErrorChecking = false;
        $dom->validateOnParse = false;
        $dom->formatOutput = true;
        $dom->xmlStandalone = false;

        // Supress the message just incase the user hasn't created 100% valid HTML so errors don't popup
        @$dom->loadHTML($html);
        
        $links = $dom->getElementsByTagName('a');

        foreach ($links as $link)
        {
            if ($link->hasAttribute('href') == true)
            {
                $href = $link->getAttribute('href');
                if (substr($href, 0, strlen('http://') != 'http://'))
                {
                    if (substr($href, 0, 1) == '/')
                    {
                        $lang_prefix = '/'.get_language();
                    }
                    else
                    {
                        $lang_prefix = get_language().'/';
                    }
                    $href = $lang_prefix.$href;

                    $link->setAttribute('href', $href);
                }
            }
        }
        
        return $dom->saveXML();
    }
    else
    {
        return $html;
    }
}

?>
