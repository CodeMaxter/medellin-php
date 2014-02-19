<?php

/**
 * It intercepts the errors and warnings from PHP and transforms it into an 
 * exception
 *
 * @author Alvaro José Agámez Licha (https://github.com/codeMaxter/)
 */
class ErrorHandler
{

    // EXTENSIONS
    public static function handler($number, $message, $file, $line)
    {
        throw new ErrorException($message, $number, 0, $file, $line);
    }

    /**
     * 
     * @param int $level
     */
    public static function register($level)
    {
        ini_set('display_errors', 1);
        error_reporting(-1);
        set_error_handler(array(get_called_class(), 'handler'));
    }

    public static function unregister()
    {
        restore_error_handler();
    }

}
