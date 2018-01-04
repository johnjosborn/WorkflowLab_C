<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';
$userID = '1';

$stepDetail = "Default";
$foundOpen = false;

$date = date("Y-m-d");

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['stp_ID'])){
    
    $stpID = $_POST['stp_ID'];
    $stpNote = $_POST['stp_note'];

    //Complete Selected Step
    $sql = "UPDATE STP
            SET STP_status = 'Complete',
                STP_USR_id = '$userID',
                STP_date = '$date',
                STP_note = '$stpNote'
            WHERE STP_id = '$stpID'";

    $result = mysqli_query($conn,$sql);

    //Look up workflow number
    $sql = "SELECT STP_WFL_id
            FROM STP
            WHERE STP_id = '$stpID'";

    $result = mysqli_query($conn,$sql);

    if($result){

        if($result->num_rows != 0){
            
            while($row = $result->fetch_assoc()){

                $wkfID = $row['STP_WFL_id'];

            }

            //Find the next open step
            $sql = "SELECT STP_id, STP_order, STP_status
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

                        $foundOpen = true;

                        break;

                        }
                    }

                    if ($foundOpen == false){
                        //no steps found to open, workflow is complete
                        $sql = "UPDATE WFL
                                SET WFL_status = 'Complete'
                                WHERE WFL_id = '$wkfID'";

                        $result = mysqli_query($conn,$sql);

                        echo "1";

                    } else {
                        echo "0";
                    }
                }
            }
        }
    }
}

?>