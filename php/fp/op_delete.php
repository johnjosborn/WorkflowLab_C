<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
require_once 'commonFunctions.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';
$userID = '1';

$output = 'initial';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['op_ID'])){

    $opID = $_POST['op_ID'];

    if(verifyOpCust($opID, $custID, $conn)){

        $storageAccount = 99;
        
        $stmt = $conn->prepare("UPDATE OPS 
            SET OPS_CUS_id = ?
            WHERE OPS_id =  $opID");
    
        $stmt->bind_param("i", $storageAccount);
        
        if($stmt->execute()){
            
            $output = '0';
            
        } else {
            
            $output = '1';
        }
        
    } else {

        $output = '3';

    }

} else {

    $output = '2';
}

echo $output;

?>