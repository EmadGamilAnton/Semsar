<?php

class Photos 
{
	private $filename;
	private $tmp_name;

    					//===================================================================================================

	public static function get_apartment_images($id)
	{
	    global $host;
		if(file_exists("images/apartments/".$id))
		{
			$images	= scandir("images/apartments/".$id);
			array_shift($images);
			array_shift($images);
			for($i=0;$i<count($images);$i++)
				$images[$i] = $host."images/apartments/".$id.'/'.$images[$i];
			return $images ;
		}
		else
			return NULL;
	}

						//========================================================================================

	public function set_profile_pic($id)
	{
		global $database;
		global $host;
		if(empty($_FILES['profile_pic']) ||!$_FILES['profile_pic'] ||!is_array($_FILES['profile_pic']) || $_FILES['profile_pic']['error']!=0)
			return false;
		else
		{
			$this->filename = rand(0 , 1000000) . basename($_FILES['profile_pic']['name']);
			$this->tmp_name = $_FILES['profile_pic']['tmp_name'];
			$target_path 	= "images/profile/" . $this->filename ;
			if(move_uploaded_file($this->tmp_name, $target_path))
			{
				$sql 		= "SELECT profile_picture FROM users WHERE user_id = {$id}";
				$old_image 	= $database->query($sql)->fetchColumn();
				$this->unlink_image($old_image);

				$sql = "UPDATE users SET profile_picture='".$host."images/profile/".$this->filename."' WHERE user_id={$id}";
				return $database->query($sql);
			}
			else
				return false;
return true;
		}
	}

	public function set_apartment_images($id)
	{
		global $database;
		if(!file_exists("images/apartments/".$id))
			mkdir("images/apartments/".$id);

		for($i=0 ; $i<count($_FILES['apartment_images']['name']) ; $i++)
		{
			$this->filename = rand(0 , 1000000) . basename($_FILES['apartment_images']['name'][$i]);
			$this->tmp_name = $_FILES['apartment_images']['tmp_name'][$i];
			$target_path 	= "images/apartments/". $id .'/'. $this->filename ;
			if(!move_uploaded_file($this->tmp_name, $target_path))
				return false;
		}
	
		return true;
	}

						//========================================================================================

	private function unlink_image($image_url)
	{
// 		$exploded_url	= explode('/',$image_url);
// 		$the_image 		= strtolower(end($exploded_url));
// 		$the_image_path = "images/profile/".$the_image;
		if(!unlink($image_url))
			$this->unlink_image($image_url);
		return ;
	}
}

?>