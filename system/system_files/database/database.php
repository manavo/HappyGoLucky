<?php

class db
{
    var $db_object;
    var $result_array;
    var $result;

    function __construct($connection = 'default')
    {
        global $config;

        try
        {
            if (!isset($config['database'][$connection]) || !is_array($config['database'][$connection]))
            {
                error("Cannot establish database connection '{$connection}'");
            }

            $db_driver_file = SYSTEM_PATH.'/system_files/database/'.$config['database'][$connection]['type'].'.php';
            if (file_exists($db_driver_file) == true)
            {
                require_once $db_driver_file;
            }
            else
            {
                error('Could not find the database driver');
            }

            // Instantiate an object depending on the type of connection specified in the config file
            $db_driver = $config['database'][$connection]['type'].'_db_driver';
            if (class_exists($db_driver) == true)
            {
                $this->db_object = new $db_driver();
            }
            else
            {
                error('Could not load database driver');
            }

            if (isset($config['database'][$connection]['port']) && $config['database'][$connection]['port'])
            {
                $this->db_object->connect($config['database'][$connection]['host'], $config['database'][$connection]['username'], $config['database'][$connection]['password'], $config['database'][$connection]['database'], $config['database'][$connection]['port']);
            }
            else
            {
                $this->db_object->connect($config['database'][$connection]['host'], $config['database'][$connection]['username'], $config['database'][$connection]['password'], $config['database'][$connection]['database']);
            }
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function  __destruct()
    {
        $this->db_object->close();
    }

    function insert_id()
    {
        return $this->db_object->insert_id();
    }

    function query($sql, $args = null)
    {
        //echo 'Query: '.$sql.'<br />Arguments:';
        //print_r($args);

        try
        {
            if (is_array($args))
            {
                foreach ($args as $k => $v)
                {
                    if ($v)
                    {
                        $args[$k] = $this->db_object->escape($v);
                    }
                }
            }

            if ($this->db_object->query($sql, $args))
            {
                $this->result = array();

                $this->result_array = $this->db_object->results;

                if (is_array($this->result_array))
                {
                    foreach ($this->result_array as $row_num => $row)
                    {
                        foreach ($row as $k => $v)
                        {
                            if ($v)
                            {
                                $row[$k] = $this->db_object->unescape($v);
                            }
                        }
                        $this->result[] = new db_result_object($row);
                        $this->result_array[$row_num] = $row;
                    }
                }

                return true;
            }
            else
            {
                return false;
            }
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function close()
    {
        @$this->db_object->close();
    }
}

class db_result_object
{
    function __construct($array) {
        if (is_array($array))
        {
            foreach ($array as $key => $value)
            {
                $this->{$key} = $value;
            }
        }
    }
}

?>