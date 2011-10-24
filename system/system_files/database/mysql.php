<?php

class mysql_db_driver implements database_interface
{
    var $db_link;
    var $results;

    function connect($host, $username, $password, $database, $port = 0)
    {
        if (!$port)
        {
            $port = 3306;
        }

        try
        {
            $this->db_link = mysql_connect($host.':'.$port, $username, $password);
            if (!$this->db_link)
            {
                error("Error connecting to database '{$config['database'][$connection]['database']}'");
            }

            if (!mysql_select_db($database))
            {
                error("Error selecting the '{$config['database'][$connection]['database']}' database");
            }
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function insert_id()
    {
        if ($this->db_link)
        {
            return mysql_insert_id($this->db_link);
        }
    }

    function close()
    {
        if ($this->db_link)
        {
            @mysql_close($this->db_link);
        }
    }

    function escape($value)
    {
        return mysql_real_escape_string($value, $this->db_link);
    }

    function unescape($value)
    {
        return stripslashes($value);
    }

    function query($sql, $args = null)
    {
        /* create a prepared statement */
        try
        {
            $arg_counter = 0;
            $pos = 0;
            while (($pos = strpos($sql, '?', $pos)) != false)
            {
                if ($arg_counter > count($args))
                {
                    error('Wrong number of arguments in SQL query');
                    return false;
                }

                if (!is_null($args[$arg_counter]))
                {
                    $query_parameter = "'".$args[$arg_counter]."'";
                }
                else
                {
                    $query_parameter = "NULL";
                }
                $sql = substr($sql, 0, $pos).$query_parameter.substr($sql, $pos+1);
                $arg_counter++;

                $pos += (strlen($query_parameter)-1);
            }

            $result_set = mysql_query($sql, $this->db_link) or error('Query failed: '.mysql_error($this->db_link));

            $results = array();

            while ($row = @mysql_fetch_assoc($result_set))
            {
                $results[] = $row;
            }

            $this->results = $results;

            return true;
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }
}

?>