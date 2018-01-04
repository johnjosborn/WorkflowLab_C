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

if (isset($_POST['stp_ID'])){
        
    $stpID = $_POST['stp_ID'];
    $stpTitle = $_POST['stp_title'];
    $stpDesc = $_POST['stp_desc'];
    $stpDetail = $_POST['stp_detail'];
    $stpNote = $_POST['stp_notes'];
    $stpUser = $_POST['stp_user'];
    $stpDate = $_POST['stp_date'];

    $stmt = $conn->prepare("UPDATE STP  
    SET STP_title = ?, STP_desc = ?, STP_detail = ?, STP_note = ? , STP_USR_id = ?, STP_date = ?
    WHERE STP_id =  $stpID");

    $stmt->bind_param("ssssis", $stpTitle, $stpDesc, $stpDetail, $stpNote, $stpUser, $stpDate);

    if($stmt->execute()){

        $output = '0';

    } else {

        $output = '1';
    }

} else {

    $output = '2';
}

echo $output;
?>