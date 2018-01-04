<?php
require "init.php";

if($_SERVER['REQUEST_METHOD']=='POST')
{
    $apartments = new Apartments();
    
    $data_recieved  = json_decode(file_get_contents("php://input"));
    $city           = $data_recieved->city;
    $min_price      = $data_recieved->min_price;
    $max_price      = $data_recieved->max_price;
    $rooms_number   = $data_recieved->rooms_number;
    $gender         = $data_recieved->gender;
    
    if(!empty($min_price))
    	$apartments->set_search_min_price($min_price);
    if(!empty($max_price))
    	$apartments->set_search_max_price($max_price);
    if(!empty($city))
    	$apartments->set_search_city($city);
    if(!empty($rooms_number))
    	$apartments->set_search_rooms_number($rooms_number);
    $apartments->set_search_gender($gender);
    
    $apartments = $apartments->get_apartments_with_conditions();
    echo json_encode($apartments);
}