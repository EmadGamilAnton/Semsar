<?php
require "init.php";

if(isset($_POST['id']))
{
    $response = array("error"=>false , "message"=>"تمام ، الشقة اختفت من البحث");
    if(!Apartments::hide_apartment($_POST['id']))
    {
        $response['error'] = true;
        $response['message'] = 'فيه حاجة غلط! جرب تاني';
    }
    echo json_encode($response);
}