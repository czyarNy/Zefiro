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
        
        $sql = "SELECT * FROM `devices` WHERE `id` = '$device_id'";
        
        if ($conn->query($sql) === TRUE) {
            $row = $sql->fetch_assoc();
		echo '{kolor="'.$row['color'].'"}';
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
