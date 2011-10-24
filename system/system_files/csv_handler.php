<?php

function csv_to_array($csv, $column_keys = null, $line_separator = "\n", $column_separator = ",")
{
    $lines = explode($line_separator, $csv);

    $return_array = array();

    foreach ($lines as $line)
    {
        if (strlen($line) > 0)
        {
            $columns = array();

            $columns = explode($column_separator, $line);

            if (is_array($column_keys))
            {
                foreach ($column_keys as $key => $value)
                {
                    if (array_key_exists($key, $columns))
                    {
                        $columns[$value] = $columns[$key];
                        unset($columns[$key]);
                    }
                }
            }

            $return_array[] = $columns;
        }
    }

    return $return_array;
}

?>