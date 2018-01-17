<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['usr_Name'])){
        
    $usr_Name = $_POST['usr_Name'];
    $usr_Email = $_POST['usr_Email'];
    $usr_Phone = $_POST['usr_Phone'];
    $usr_Access  = $_POST['usr_Access'];

    $stmt = $conn->prepare("INSERT INTO USR (USR_name, USR_perm, USR_email, usr_Phone, USR_CUS_id) VALUES (?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sissi", $usr_Name, $usr_Access, $usr_Email, $usr_Phone, $custID);
    
    if($stmt->execute()){
    
        $output = $stmt->insert_id;;
    
    } else {
    
        $output = '1';
    }
    
} else {

    $output = '2';

}

echo $output;

?>