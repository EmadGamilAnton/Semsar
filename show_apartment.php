<?php
require "init.php";

if(isset($_POST['id']))
{
    $response = array("error"=>false , 'message'=>'تمام ، الشقة هتظهر في البحث');
    if(!Apartments::show_apartment($_POST['id']))
    {
        $response['error'] = true;
        $response['message'] = 'فيه حاجة غلط! جرب تاني';
    }
    echo json_encode($response);
}