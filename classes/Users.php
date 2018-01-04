<?php

class Users
{
	private $phone_number ;
	private $password ;
    private $full_name ;
    private $profile_picture ;
    public  $error;

    public function set_phone_number($phone_number)
    {
        $this->phone_number = strval($phone_number);
    }
    public function set_password($password)
    {
        $this->password = sha1($password);
    }
    public function set_full_name($full_name)
    {
        $filtered_full_name = $this->string_filter($full_name);
        $this->full_name = $filtered_full_name;
    }

	public function create_user()
	{
		global $database ;
		if($this->check_phone_number())
        {
		    $this->error= "رقم التليفون دة متسجل";
            return false;
        }

        else
        {
            $stmt ="INSERT INTO users(full_name , password , phone_number , profile_picture , date_registered ) 
				    VALUES ('{$this->full_name}' , '{$this->password}' , '{$this->phone_number}' , 'https://mina68.000webhostapp.com/maskan/users/images/profile/unknown.jpg' , now()) ";
            if($database->query($stmt))
                return $database->query("SELECT * FROM users WHERE phone_number='{$this->phone_number}'")->fetch(PDO::FETCH_ASSOC);
            else
            {
                $this->error= "فيه حاجة غلط! جرب تاني";
                return false;
            }
        }
	}

    public static function get_user_by_id($id)
    {
        global $database;
        $sql = "SELECT * FROM users WHERE user_id=" . $id;
        return $database->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public static function get_user_by_phone_number($phone)
    {
        global $database;
        $sql = "SELECT * FROM users WHERE phone_number=" . $phone;
        return $database->query($sql)->fetch(PDO::FETCH_ASSOC);
    }

    public function update_phone_number($id)
    {
        global $database;
        if($this->check_phone_number($id))
        {
            $this->error = "رقم التليفون دة متسجل";
            return false;
        }

        $stmt = "UPDATE users SET phone_number = '{$this->phone_number}' WHERE user_id = {$id}";
        return $database->query($stmt);
    }

	public function verify_user()	
	{
		global $database ;
		$result = $database->query("SELECT * FROM users WHERE password='{$this->password}' AND phone_number='{$this->phone_number}'")->fetch(PDO::FETCH_ASSOC);
		if($result)
		    return $result;
		else
		{
		    $this->error = "اتاكد من رقم التليفون و الباسورد";
		    return false ;
		}
	}

    private function check_phone_number($id = 0)
    {
        global $database ;
        $count=$database->query("SELECT * FROM users WHERE phone_number = '{$this->phone_number}' 
                                AND user_id != {$id}")->rowCount();
        return ($count==1) ? true : false ;
    }
    
    private function string_filter($string)
    {
        return filter_var($string , FILTER_SANITIZE_STRING , FILTER_FLAG_NO_ENCODE_QUOTES);
    }

    private function integer_filter($int)
    {
        return filter_var($int , FILTER_SANITIZE_NUMBER_INT , FILTER_FLAG_NO_ENCODE_QUOTES);
    }
}