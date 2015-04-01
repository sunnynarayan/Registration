<?php

require("dbcon.php");

//functions used to validate and prepare data to store in the database 

function prepare_input($data) {
    return htmlspecialchars(stripcslashes(trim($data)));
}

function prepare_mobile($phno) {
    return $phno = preg_replace('/[^0-9]/', '', $phno);
}


function check_text($data) {
    if (empty($data)) {
        return 'Can\'t be blank';
    } elseif (!preg_match('/^[a-zA-Z]*$/', $data)) {
        return "Only letters are allowed!<br>";
    } else
        return '';
}

function check_mobile($phno) {
    if(!preg_match('/^[56789][0-9]{9}$/', $phno)){ 
        return "check your Mobile number ";
    }  
    if (!(strlen($phno) == 10))
        return'Invalid number format<br>';

    return '';
}

function check_email($email) {
    if (empty($email))
        return 'Can\'t be blank';
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL))
        return 'Invalid email format<br>';
    else
        return '';
}

function make_text($data){
    return preg_replace('/[^a-zA-Z]/','', $data); //deletes all the non-alphabets
}

function make_numeric($data){
    return preg_replace('/[^0-9]/', '', $data);
}

//Initialising variables
$name = "";
$mobile = "";
$email = "";
$img_extension="";
$pdf_extension="";
$out1=false;
$out2=false;
$out3=false;

//checking if the form is submitted
if (isset($_POST['FirstN'])) {
	$nameErr = '';
	$mobileErr = '';
	$emailErr = '';

    $Fname = ucfirst(strtolower(make_text(prepare_input($_POST['FirstN'])))); //storing the result into variables after validation
    $Lname = ucfirst(strtolower(make_text(prepare_input($_POST['LastN']))));
	$name = $Fname .' '.$Lname;
	$mobile = make_numeric(prepare_mobile($_POST['mobile']));
    $email = prepare_input($_POST['email']);

    $mobileErr = check_mobile($mobile); //validating data
    $emailErr = check_email($email);

    if ($mobileErr){
        $out3 = true;
        echo "<span id='invalid'><b>".$mobileErr."</b></span>";
    }
    if ($emailErr){
        $out3 = true;
        echo "<span id='invalid'><b>".$emailErr."</b></span>";
    }

} else {
	$out = true;
    $nameErr = 'Only alphabets are allowed';
    $mobileErr = 'Must be unique';
    $emailErr = 'Must be unique';
}

//checking if the image is submitted
if(isset($_FILES["pic"]["type"])){
	$validextensions = array("jpeg", "jpg", "png");
	$temporary = explode(".", $_FILES["pic"]["name"]);
	$img_extension = end($temporary);
	if ((($_FILES["pic"]["type"] == "image/png") || ($_FILES["pic"]["type"] == "image/jpg") || ($_FILES["pic"]["type"] == "image/jpeg")
	) && ($_FILES["pic"]["size"] < 102400)//Approx. 100kb files can be uploaded.
	&& in_array($img_extension, $validextensions)) {
		if ($_FILES["pic"]["error"] > 0){
			echo "Return Code: " . $_FILES["pic"]["error"] . "<br/><br/>";
		}else{
			$out1=true;
		}
	}
}else{
	echo "<span id='invalid'>***Invalid pic Size or Type***<span>";
}

//checking if the resume is submitted
if(isset($_FILES["resume"]["type"])){
	$temporary = explode(".", $_FILES["resume"]["name"]);
	$pdf_extension = end($temporary);
	if (($_FILES["resume"]["type"] == "application/pdf") && ($_FILES["resume"]["size"] < 102400) && ($pdf_extension=="pdf")) {
		if ($_FILES["resume"]["error"] > 0){
			echo "Return Code: " . $_FILES["resume"]["error"] . "<br/><br/>";
		}else{
			$out2=true;
		}
	}
}else{
	echo "<span id='invalid'>***Invalid resume Size or Type***<span>";
}

//Adding data to the database and adding files if valid
if ((!$out3) && $out1 && $out2) {
	$sql = "INSERT INTO user_details (name,mobile,email) VALUES ('$name', $mobile, '$email') ";
	$result = mysqli_query($db_connection, $sql);
    if (!$result) {
        $Err = 'Mobile or Email is already registered <br>Or there might be problem in connection to database. ';  //give error msg
        mysqli_close($db_connection);
        exit("<span id='invalid'><b>".$Err."</b></span>");
    }
    $qry = "SELECT * FROM user_details WHERE mobile=$mobile";
    
    $result = mysqli_query($db_connection, $qry);
    if (!$result) {
        $Err = 'Database connection problem';  //give error msg
        mysqli_close($db_connection);
        exit("<span id='invalid'><b>".$Err."</b></span>");
    }else{
        $row = mysqli_fetch_array($result);
        $id = $row['id'];
        if (!file_exists($id)) {
            mkdir($id);
        }
        $dir = $id;
        
        $filename = $_FILES['pic']['name'];
        $newname = $id."_image".$img_extension;
        $tmp_name = $_FILES['pic']['tmp_name'];
        move_uploaded_file($tmp_name, $dir . "/" . $newname);

        $filename = $_FILES['resume']['name'];
        $newname = $id."_resume.pdf";
        $tmp_name = $_FILES['resume']['tmp_name'];
        move_uploaded_file($tmp_name, $dir . "/" . $newname);
        echo "<span id='success'>All details Uploaded Successfully...!!</span><br/>"; //Success message
	} 
}else{ //Error message
    if (!$out1){
        echo "<span id='invalid'><b>Invalid Image File!!!</b></span>";
    }
    if (!$out2){
        echo "<span id='invalid'><b>Invalid Resume File!!!</b></span>";
    }
}

?>