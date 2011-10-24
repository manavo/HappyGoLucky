<?php

class hgl_exception extends Exception
{
    function  __toString()
    {
        return $this->getMessage();
    }
}

?>