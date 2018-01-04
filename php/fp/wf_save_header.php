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

if (isset($_POST['wkf_ID'])){
        
    $wkfID = $_POST['wkf_ID'];
    $wkf_Num = $_POST['wkf_Num'];
    $wkf_Item = $_POST['wkf_Item'];
    $wkf_Desc = $_POST['wkf_Desc'];
    $wkf_Sta  = $_POST['wkf_Sta'];
    $wkf_Ref  = $_POST['wkf_Ref'];
    $wkf_Grp  = $_POST['wkf_Grp'];
    $wkf_Not  = $_POST['wkf_Not'];

    $stmt = $conn->prepare("UPDATE WFL  
    SET WFL_num = ?, WFL_item = ?, WFL_desc = ?, WFL_status = ?, WFL_ref = ? , WFL_group = ?, WFL_notes = ?
    WHERE WFL_CUS_id = $custID AND WFL_id =  $wkfID");

    $stmt->bind_param("sssssss", $wkf_Num, $wkf_Item, $wkf_Desc, $wkf_Sta, $wkf_Ref, $wkf_Grp, $wkf_Not);

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