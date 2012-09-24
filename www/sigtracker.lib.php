<?php

function jsonExit($data)
{
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

// found in mytinytodo common.php
class DBConnection
{
    protected static $instance;

    public static function init($instance)
    {
        self::$instance = $instance;
        return $instance;
    }

    public static function instance()
    {
        if (!isset(self::$instance)) {
            $c = 'DBConnection';
            self::$instance = new $c;
        }
        return self::$instance;
    }
}


?>