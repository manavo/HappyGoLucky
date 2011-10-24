<?php

class mysqli_db_driver implements database_interface
{
    var $mysqli;
    var $stmt;
    var $result;
    var $results;

    function connect($host, $username, $password, $database, $port = 0)
    {
        try
        {
            if (!$port)
            {
                $port = 3306;
            }
            $this->mysqli = new mysqli($host, $username, $password, $database, $port);
            if ($this->mysqli->connect_error)
            {
                error("Error connecting to database '{$config['database'][$connection]['database']}'");
            }
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function close()
    {
        @$this->mysqli->close();
    }

    function query($sql, $args = null)
    {
        /* create a prepared statement */
        try
        {
            $this->stmt = $this->mysqli->prepare($sql);
            if ($this->stmt)
            {
                if (is_array($args))
                {
                    $params = array();
                    $params[] = "";
                    foreach ($args as $arg)
                    {
                        $params[0] .= "s";
                        $params[] = $arg;
                    }

                    foreach($params as $key => $value) $tmp[$key] = &$params[$key];
                    call_user_func_array(array($this->stmt, 'bind_param'), $tmp);
                }

                /* execute prepared statement */
                if (!$this->stmt->execute())
                {
                    error('Query execution failed! (Error: '.$this->stmt->error.') (Query: '.$sql.') (Parameters: '.implode(',', $params).')');
                    return false;
                }

                $this->stmt->store_result();

                $meta = $this->stmt->result_metadata();

                $results = array();

                if ($meta)
                {
                    while ($column = $meta->fetch_field()) {
                        $columnName = str_replace(' ', '_', $column->name);
                        $bindVarArray[] = &$this->stmt->results[$columnName];
                    }
                    call_user_func_array(array($this->stmt, 'bind_result'), $bindVarArray);

                    while ($this->stmt->fetch() != null)
                    {
                        $row = array();

                        foreach ($this->stmt->results as $k => $v) {
                            $row[$k] = $v;
                        }

                        $results[] = $row;
                    }
                }

                $this->results = $results;

                return true;
            }
            else
            {
                error('Could not prepare statement ('.$this->mysqli->error.')');
                return false;
            }
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    // In mysqli we don't need to escape or unescape because we have the binding functions
    function escape($value)
    {
        return $value;
    }

    // In mysqli we don't need to escape or unescape because we have the binding functions
    function unescape($value)
    {
        return $value;
    }

    function insert_id()
    {
        return $this->mysqli->insert_id;
    }
}

?>