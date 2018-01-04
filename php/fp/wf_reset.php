<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

$stepDetail = "Default";
$foundOpen = false;

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['wkf_ID'])){
    
    $wkfID = $_POST['wkf_ID'];

    //Set all steps to pending
    $sql = "UPDATE STP
            SET STP_status = 'Pending',
                STP_USR_id = '',
                STP_date = '',
                STP_note = ''
            WHERE STP_WFL_id = '$wkfID'";

    $result = mysqli_query($conn,$sql);

    //Open the first step
    $sql = "SELECT STP_id, STP_status
            FROM STP
            WHERE STP_WFL_id = '$wkfID'
            ORDER BY STP_order";

    $result = mysqli_query($conn,$sql);

    if($result){

        if($result->num_rows != 0){
            
            while($row = $result->fetch_assoc()){

                if($row['STP_status'] == 'Pending'){
                    $stpID = $row['STP_id'];

                //Open the step
                $sql = "UPDATE STP
                        SET STP_status = 'Open'
                        WHERE STP_id = '$stpID'";

                $result = mysqli_query($conn,$sql);

                break;

                }
            }
        }
    }

    //Set work flow to active
    $sql = "UPDATE WFL
    SET WFL_status = 'Active'
    WHERE WFL_CUS_id = '$custID' AND WFL_id = '$wkfID'";

    $result = mysqli_query($conn,$sql);
}

?>  