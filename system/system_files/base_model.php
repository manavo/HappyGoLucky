<?php

class model
{
    // Get overriden in the models that extend this (for quick_load and quick_save to work)
    var $table = null;
    var $primary_key = null;

    // Only for internal use within the model
    private $_db = null;
    private $_loaded_from_db = false;
    private $_attributes = array();

    function __get($name)
    {
        if (isset($this->_attributes[$name]))
        {
            return $this->_attributes[$name];
        }

        if (isset($this->{$name}))
        {
            return $this->{$name};
        }

        return null;
    }

    function __set($name, $value)
    {
        $this->_attributes[$name] = $value;
    }

    function __construct($id = 0)
    {
        $this->_db = new db();

        try
        {
            if ($id)
            {
                $this->load($id);
            }
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function  __destruct()
    {
        $this->_db->close();
    }

    function get_attributes()
    {
        return $this->_attributes;
    }

    function load($id=0)
    {
        try
        {
            if ($id)
            {
                return $this->quick_load($id, $this->table, $this->primary_key);
            }

            return false;
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function save()
    {
        try
        {
            return $this->quick_save($this->{$this->primary_key}, $this->table, $this->primary_key);
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function quick_load($id = null, $table = null, $primary_key = null)
    {
        try
        {
            if (!is_null($id) && !is_null($table) && !is_null($primary_key))
            {
                $db = new db();
                $sql = "SELECT * FROM `{$table}` WHERE `{$primary_key}` = ?";
                $db->query($sql, array($id));

                $resultset = $db->result_array;

                if (count($resultset) == 1)
                {
                    $this->_loaded_from_db = true;

                    $row = $resultset[0];

                    foreach ($row as $key => $value)
                    {
                        $this->{$key} = $value;
                    }

                    return true;
                }
                else
                {
                    //error('Not found!');
                    return false;
                }
            }

            return false;
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function quick_save($id = null, $table = null, $primary_key = null)
    {
        try
        {
            if (!is_null($table) && !is_null($primary_key))
            {
                $values = array();

                if ($this->_loaded_from_db == true)
                {
                    //update
                    $conditions = array();

                    foreach ($this->_attributes as $key => $value)
                    {
                        $conditions[] = "`{$key}` = ?";
                        $values[] = $value;
                    }

                    if (count($conditions) > 0 && count($values) > 0)
                    {
                        $sql = "UPDATE `{$table}` SET ";
                        $sql .= implode(", ", $conditions);
                        $sql .= " WHERE `{$primary_key}` = ?";

                        $values[] = $this->{$this->primary_key};
                    }
                }
                else
                {
                    //create
                    $columns = array();
                    $qs = array();

                    foreach ($this->_attributes as $key => $value)
                    {
                        $columns[] = "`".$key."`";
                        $qs[] = "?";
                        $values[] = $value;
                    }

                    if (count($columns) > 0 && count($values) > 0)
                    {
                        $sql = "INSERT INTO `{$table}` (";
                        $sql .= implode(", ", $columns);
                        $sql .= ") VALUES (".implode(", ", $qs).")";
                    }
                }
                if (isset($sql))
                {
                    $db = new db();
                    if ($db->query($sql, $values))
                    {
                        if ($this->_loaded_from_db == false)
                        {
                            $new_id = $db->insert_id();
                            $this->{$this->primary_key} = $new_id;
                            $this->_loaded_from_db = true;
                        }

                        return true;
                    }
                    else
                    {
                        return false;
                    }
                }
                else
                {
                    error('Could not save. Please check the logic of your controller!');

                    return false;
                }
            }

            return false;
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function delete()
    {
        try
        {
            $db = new db();
            $sql = "DELETE FROM `{$this->table}` WHERE `{$this->primary_key}` = ?";
            return $db->query($sql, array($this->{$this->primary_key}));
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

    function database_info()
    {
        try
        {
            $db = new db();
            $sql = "DESCRIBE `{$this->table}`";
            $db->query($sql);

            return $db->result_array;
        }
        catch (hgl_exception $e)
        {
            throw $e;
        }
    }

}

?>