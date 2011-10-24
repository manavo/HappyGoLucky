<?php

function sanitize_data($data)
{
    global $config;

    if (is_array($data))
    {
        foreach ($data as $key => $d)
        {
            $data[$key] = sanitize_data($d);
        }
        return $data;
    }

    if (get_magic_quotes_gpc() == 1 || get_magic_quotes_runtime() == 1)
    {
        $data = stripslashes($data);
    }

    if (isset($config['security_data_filters']))
    {
        if (in_array('xss', $config['security_data_filters']) == true)
        {
            // XSS clean
            $data = str_replace(array("<script","</script>"), array("&lt;script","&lt;/script&gt;"), $data);
        }
        if (in_array('html', $config['security_data_filters']) == true)
        {
            // HTML clean
            $data = htmlentities($data);
        }
    }
    else
    {
        error('No security data filters have been enabled! You really should change that!');
    }

    return $data;
}

?>