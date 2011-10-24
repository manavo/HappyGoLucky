<?php

$form_validation_errors = array();

function form_error_html($field, $wrapper_classname = '')
{
    global $form_validation_errors;

    $html = '';

    if (isset($form_validation_errors[$field]) && $form_validation_errors[$field])
    {
        $html = form_errors_html($wrapper_classname, $form_validation_errors[$field]);
    }

    return $html;
}

function form_errors_html($wrapper_classname = '', $errors = array())
{
    global $form_validation_errors;

    if (!is_array($errors) || count($errors) == 0)
    {
        $errors = $form_validation_errors;
    }

    $errors_html = '';

    if (is_array($errors) == true)
    {
        foreach ($errors as $error)
        {
            if (!is_array($error))
            {
                $errors_html .= '<div>'.$error.'</div>';
            }
            else
            {
                if (count($error) > 0)
                {
                    $errors_html .= form_errors_html('', $error);
                }
            }
        }
    }

    if ($wrapper_classname)
    {
        $html = '<div';
        $html .= ' class="'.$wrapper_classname.'"';
        $html .= '>'.$errors_html.'</div>';
    }
    else
    {
        $html = $errors_html;
    }

    return $html;
}

function form_validation($config = array())
{
    global $form_validation_errors;

    $form_validation_errors = array();

    $validation_ok = true;

    if (form_posted() == true)
    {
        if (is_array($config) == true)
        {
            foreach ($config as $field => $validation)
            {
                $validations = explode('|', $validation);

                $form_validation_errors[$field] = array();

                foreach ($validations as $function)
                {
                    try
                    {
                        $bracket_position = strpos($function, '[');
                        if ($bracket_position === false) {
                            if (is_callable($function)) {
                                $function($field);
                            }
                        } else {
                            $function_name = substr($function, 0, $bracket_position);
                            $parameter = substr($function, $bracket_position+1);
                            $parameter = rtrim($parameter, ']');

                            if (is_callable($function_name)) {
                                $function_name($field, $parameter);
                            }
                        }
                    }
                    catch (hgl_exception $e)
                    {
                        array_push($form_validation_errors[$field], $e);
                        $validation_ok = false;
                        break;
                    }
                }
            }
        }
    }
    else
    {
        $validation_ok = false;
    }

    return $validation_ok;
}

function form_text_value($name, $default = '')
{
    if (isset($_POST[$name]))
    {
        return $_POST[$name];
    }

    return $default;
}

function form_option_value($name, $value, $default_selected = false)
{
    if (isset($_POST[$name]) && $_POST[$name] == $value)
    {
        $selected = ' selected="selected"';
    }
    else
    {
        if ($default_selected == true)
        {
            $selected = ' selected="selected"';
        }
        else
        {
            $selected = '';
        }

    }

    return $selected;
}

function form_checkbox_value($name, $default_checked = false)
{
    if (isset($_POST[$name]))
    {
        $checked = ' checked="checked"';
    }
    else if (!isset($_POST[$name]) && form_posted() == true)
    {
        $checked = '';
    }
    else
    {
        if ($default_checked == true)
        {
            $checked = ' checked="checked"';
        }
        else
        {
            $checked = '';
        }
    }

    return $checked;
}

function form_radio_value($name, $value, $default_checked = false)
{
    if (isset($_POST[$name]))
    {
        if (is_array($_POST[$name]) && count($_POST[$name]) == 1)
        {
            $_POST[$name] = $_POST[$name][0];
        }
    }

    if (isset($_POST[$name]) && $_POST[$name] == $value)
    {
        $checked = ' checked="checked"';
    }
    else if (!isset($_POST[$name]) && form_posted() == true)
    {
        $checked = '';
    }
    else
    {
        if ($default_checked == true)
        {
            $checked = ' checked="checked"';
        }
        else
        {
            $checked = '';
        }
    }

    return $checked;
}

function form_posted()
{
    if (strtoupper($_SERVER['REQUEST_METHOD']) == 'POST')
    {
        return true;
    }
    else
    {
        return false;
    }
}

?>