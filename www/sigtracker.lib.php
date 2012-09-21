<?php

function jsonExit($data)
{
    header('Content-type: application/json; charset=utf-8');
    echo json_encode($data);
    exit;
}

?>