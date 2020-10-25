<?php

function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}


$servername = "szkolnewybory.pl";
$dbname = "admin_zephyrior";
$username = "zephyrior";
$password = "6bny2B0&";

$api_key_value = "tPmAT5Ab3j7F9";


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $api_key = test_input($_POST["api_key"]);
    if($api_key == $api_key_value) {
        
       
        $conn = new mysqli($servername, $username, $password, $dbname);
       
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } 
		
		$device_id = test_input($_POST['id']);
		$error = test_input($_POST['error']);
		$pm1 = test_input($_POST['pm1']);
		$pm25 = test_input($_POST['pm25']);
		$pm10 = test_input($_POST['pm10']);
		$humd = test_input($_POST['humidity']);
		$temp = test_input($_POST['temp']);
		$prea = test_input($_POST['pressure']);
        
        $sql = "INSERT INTO `logs`(`id`, `error`, `pm1`, `pm25`, `pm10`, `humidity`, `temp`, `pressure`)
		VALUES ('$device_id',$error, $pm1, $pm25,$pm10, $humd, $temp, $prea)";
        
        if ($conn->query($sql) === TRUE) {
            echo "OK";
        } 
        else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    
        $conn->close();
    }
    else {
        echo "Zle API.";
    }

}
else {
    echo "NO POST.";
}
