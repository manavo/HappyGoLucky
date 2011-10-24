<?php

function error($message)
{
    if (output_buffering() == true)
    {
        if (ob_get_length())
        {
            ob_end_flush();
        }
    }

    //die($message);
    throw new hgl_exception($message);
}

?>