<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

//default declaration values
$stepIndex = 0;
$totalSteps = 0;
$compSteps = 0;
$i = 0;

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['wf_id'])){

    $wfID = $_POST['wf_id'];

    //All the steps
    $sql = "SELECT STP_id, STP_status
            FROM STP
            WHERE STP_WFL_id = '$wfID'
            ORDER By STP_order";

            $stepIndex = $sql;
            
    $result = mysqli_query($conn,$sql);

    if($result){

        if($result->num_rows != 0){

            $stepIndex = 0;

            while($row = $result->fetch_assoc()){

                $thisStatus = $row['STP_status'];
                $totalSteps++;

                switch($thisStatus){

                    case "Open":
                        $stepIndex = $i;
                    break;

                    case "Complete":
                        $compSteps++;
                    break;
                }

                $i++;
            }
        }
    } 

} 

echo json_encode(array($stepIndex, $compSteps, $totalSteps)); 

?>