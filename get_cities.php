<?php

if($_SERVER['REQUEST_METHOD']== 'POST')
{
    $cities = array(
        "منوف"
        );
        
    echo json_encode($cities);
}