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

if (isset($_POST['op_title'])){
        
    $opTitle = $_POST['op_title'];
    $opDesc = $_POST['op_desc'];
    $opDetail = $_POST['op_detail'];
    $opType = $_POST['op_type'];
    $opSta  = $_POST['op_sta'];

    $stmt = $conn->prepare("INSERT INTO OPS (OPS_title, OPS_desc, OPS_detail, OPS_type, OPS_status, OPS_CUS_id) VALUES (?, ?, ?, ?, ?, ?)");
    
    $stmt->bind_param("sssssi", $opTitle, $opDesc, $opDetail, $opType, $opSta, $custID);
    
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