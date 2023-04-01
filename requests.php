<?php

  header("Acess-Control-Allow-Methods: POST");
  header("Acess-Control-Allow-Headers: Acess-Control-Allow-Headers,Content-Type,Acess-Control-Allow-Methods, Authorization");
  header("Content-Type: application/json; charset=UTF-8");


require 'dbconfig.php';

$data = json_decode(file_get_contents("php://input"), true);
if(isset($_FILES['sendimage'])){
    $temp  = explode(".",$_FILES['sendimage']['name']);
    $tempPath  =  $_FILES['sendimage']['tmp_name'];
    $fileSize  =  $_FILES['sendimage']['size'];
    $final_name = $_POST['fileName'];
}
		
if(empty($tempPath))
{
	$errorMSG = json_encode(array("message" => "please select image", "status" => false));	
	// return $errorMSG;
}
else
{
	$upload_path = 'uploads/';
	$valid_extensions = array('jpeg', 'jpg', 'png', 'gif'); 
					
	// allow valid image file formats
	if(in_array($temp[1], $valid_extensions))
	{				
		//check file not exist our upload folder path
		if(!file_exists($upload_path . $final_name))
		{
			// check file size '5MB'
			if($fileSize < 5000000){
				move_uploaded_file($tempPath, $upload_path . $final_name);
			}
			else{		
				$errorMSG = json_encode(array("message" => "Sorry, your file is too large, please upload 5 MB size", "status" => false));	
			}
		}
		else
		{		
			$errorMSG = json_encode(array("message" => "Sorry, file already exists check upload folder", "status" => false));	
		}
	}
	else
	{		
		$errorMSG = json_encode(array("message" => "Sorry, only JPG, JPEG, PNG & GIF files are allowed", "status" => false));	
	}
}
		
if(!isset($errorMSG))
{
	$query = mysqli_query($conn,'INSERT into tbl_image (nome) VALUES("'.$final_name.'")');

	$msg = json_encode(array("message" => "Image Uploaded Successfully", "url" => $final_name, "status" => 200));	
    header("HTTP/1.1 200 $msg");
    echo $msg;
    exit();
  
}else{
  header("HTTP/1.1 500 $errorMSG");
  exit();
}

?>
