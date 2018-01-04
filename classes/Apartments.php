<?php

class Apartments
{
    private $address;
    private $owner_id;
    private $price;
    private $rooms_number;
    private $description;
    private $floor;
    private $availability;
    private $city;
    private $gender;

    private $search_min_price =0;
    private $search_max_price =100000;
    private $search_city;
    private $search_rooms_number;
    private $search_gender;
    public  $error;

    public function set_address($address)
    {
        $filtered_address = $this->string_filter($address);
        $this->address = $filtered_address;
    }
    public function set_owner_id($owner_id)
    {
        $filtered_owner_id = $this->integer_filter($owner_id);
        $this->owner_id = $filtered_owner_id;
    }
    public function set_price($price)
    {
        $filtered_price = $this->integer_filter($price);
        $this->price = $filtered_price;
    }
    public function set_rooms_number($rooms_number)
    {
        $filtered_rooms_number = $this->integer_filter($rooms_number);
        $this->rooms_number = $filtered_rooms_number;
    }
    public function set_gender($gender)
    {
        $filtered_gender = $this->integer_filter($gender);
        $this->gender = $filtered_gender;
    }
    public function set_description($description)
    {
        $filtered_description = $this->string_filter($description);
        $this->description = $filtered_description;
    }
    public function set_availability($availability)
    {
        $this->availability = $availability;
    }
    public function set_floor($floor)
    {
        $filtered_floor = $this->integer_filter($floor);
        $this->floor = $filtered_floor;
    }
    public function set_city($city)
    {
        $filtered_city = $this->string_filter($city);
        $this->city = $filtered_city;
    }
    public function set_search_city($search_city)
    {
        $filtered_search_city = $this->string_filter($search_city);
        $this->search_city = $filtered_search_city;
    }
    public function set_search_rooms_number($search_rooms_number)
    {
        $this->search_rooms_number = $search_rooms_number;
    }
    public function set_search_min_price($search_min_price)
    {
        $filtered_search_min_price = $this->integer_filter($search_min_price);
        $this->search_min_price = $filtered_search_min_price;
    }
    public function set_search_max_price($search_max_price)
    {
        $filtered_search_max_price = $this->integer_filter($search_max_price);
        $this->search_max_price = $filtered_search_max_price;
    }
    public function set_search_gender($search_gender)
    {
        if($search_gender == 1)
            $this->search_gender = "(1,3)";
        elseif($search_gender == 2)
            $this->search_gender = "(2,3)";
        elseif($search_gender == 3)
            $this->search_gender = "(1,2,3)";
    }


    public function add_apartment()
    {
        global $database;
        $stmt ="INSERT INTO apartments(address , owner_id , price , rooms_number , description , floor , city , gender , availability , date) 
				    VALUES ('{$this->address}' , {$this->owner_id} , {$this->price} , {$this->rooms_number} , '{$this->description}' , {$this->floor} , '{$this->city}', {$this->gender} , 1 , now())";
        if($database->query($stmt))
        {
            $id = $database->connect->lastInsertId();
            $image = new Photos();
            if($image->set_apartment_images($id))
                return true;
            else
            {
                Apartments::delete_apartment($id);
                $this->error='فيه حاجة غلط! جرب تاني';
                return false;
            }
        }
        else
        {
            $this->error='فيه حاجة غلط! جرب تاني';
            return false;
        }
    }

                //=============================================================================================================

    public function get_apartments_with_conditions()
    {
        global $database;
        $stmt = "SELECT * FROM apartments WHERE availability = 1 AND approved=1 AND gender IN {$this->search_gender} AND price BETWEEN {$this->search_min_price} AND {$this->search_max_price} ";
        if((!empty($this->search_city)))
            $stmt .= "AND city = '{$this->search_city}' ";
        if(!empty($this->search_rooms_number))
        {
            $search_rooms_number = implode(',' , $this->search_rooms_number);
            $search_rooms_number = '(' . $search_rooms_number . ')' ;
            $stmt .= "AND rooms_number IN {$search_rooms_number} ";
        }

        $apartments = $database->query($stmt)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($apartments as &$apartment) {
            $apartment['images'] = Photos::get_apartment_images($apartment['apartment_id']);
            $user_data = $this->get_owner_data($apartment['owner_id']);
            $apartment['full_name'] = $user_data['full_name'];
            $apartment['phone_number'] = $user_data['phone_number'];
            $apartment['profile_picture'] = $user_data['profile_picture'];
        }
        return $apartments;
    }

                //=============================================================================================================

    public static function get_apartment_by_id($id)
    {
        global $database;
        $sql = "SELECT * FROM apartments WHERE apartment_id={$id}";
        $apartment = $database->query($sql)->fetch(PDO::FETCH_ASSOC);
        $apartment['images'] = Photos::get_apartment_images($id);
        return $apartment;
    }

                //=============================================================================================================

    public function update_apartment()
    {
        global $database;
        $stmt = "UPDATE apartments SET address='{$this->address}' , city='{$this->city}' , available_rooms={$this->available_rooms} , rooms_number={$this->rooms_number} , price={$this->price} , description='{$this->description}' , floor={$this->floor} WHERE apartment_id={$this->apartment_id}";
        return $database->query($stmt) ? true : false;
    }

                //=============================================================================================================

    public static function delete_apartment($id)
    {
        global $database;
        $stmt = "DELETE FROM apartments WHERE apartment_id={$id}";
        return $database->query($stmt) ? true : false;
    }

                //=============================================================================================================

    public static function get_owner_apartments($id)
    {
        global $database;
        $stmt = "SELECT * FROM apartments WHERE owner_id={$id}";
        $apartments = $database->query($stmt)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($apartments as &$apartment) {
            $apartment['images'] = Photos::get_apartment_images($apartment['apartment_id']);
        }
        return $apartments;
    }

                //=============================================================================================================

    public static function approve_apartment($id)
    {
        global $database;
        $stmt = "UPDATE apartments SET approved = 1 WHERE apartment_id={$id}";
        return $database->query($stmt) ? true : false ;
    }
    
    public function increment_apartment_views($id)
    {
        global $database ;
        $stmt = "UPDATE apartments SET views = 1+(SELECT views WHERE apartment_id={$id})
         WHERE apartment_id={$id}";
        $database->query($stmt);
        return ;
    }
    
    public function show_apartment($id)
    {
        global $database;
        $stmt = "UPDATE apartments SET availability=1 WHERE apartment_id={$id}";
        return $database->query($stmt);
    }
    
    public function hide_apartment($id)
    {
        global $database;
        $stmt = "UPDATE apartments SET availability=0 WHERE apartment_id={$id}";
        return $database->query($stmt);
    }

    private function get_owner_data($id)
    {
        global $database ;
        $stmt = "SELECT full_name , phone_number , profile_picture FROM users WHERE user_id={$id}";
        return $database->query($stmt)->fetch(PDO::FETCH_ASSOC);
    }

                //=============================================================================================================

    private function string_filter($string)
    {
        return filter_var($string , FILTER_SANITIZE_STRING , FILTER_FLAG_NO_ENCODE_QUOTES);
    }

                //=============================================================================================================

    private function integer_filter($int)
    {
        return filter_var($int , FILTER_SANITIZE_NUMBER_INT , FILTER_FLAG_NO_ENCODE_QUOTES);
    }
}