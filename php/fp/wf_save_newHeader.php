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

$wkf_Num = $_POST['wkf_Num'];
$wkf_Item = $_POST['wkf_Item'];
$wkf_Desc = $_POST['wkf_Desc'];
$wkf_Sta  = $_POST['wkf_Sta'];
$wkf_Ref  = $_POST['wkf_Ref'];
$wkf_Grp  = $_POST['wkf_Grp'];
$wkf_Not  = $_POST['wkf_Not'];

$stmt = $conn->prepare("INSERT INTO WFL (WFL_CUS_id, WFL_num, WFL_status, WFL_notes, WFL_group, WFL_item, WFL_desc, WFL_ref) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

$stmt->bind_param("isssssss", $custID, $wkf_Num, $wkf_Sta, $wkf_Not, $wkf_Grp, $wkf_Item, $wkf_Desc, $wkf_Ref);

if($stmt->execute()){

    $output = $stmt->insert_id;;

} else {

    $output = '1';
}

echo $output;
?>