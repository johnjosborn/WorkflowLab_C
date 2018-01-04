<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';
$userID = '1';

$output = 'initial';

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['wkf_ID'])){

    $wkfID = $_POST['wkf_ID'];

    if (isset($_POST['stepOrder'])){

        $setOrder = ($_POST['stepOrder']);

        $seq = 0;

        //generate a unique number to serve as the job sheet
        $tempWorkflow = $userID . "999";

        $output = $tempWorkflow;

        foreach ($setOrder as $op){

            //DEBUG what to do if there are no steps at all
        
            ++$seq;
                
            if (strpos($op , "x")) {
            
                //this is an existing operation, copy it to the tempoary user jobsheet
            
                $opID = str_replace("x","", $op);
                $opID = str_replace("opNum=","", $opID);
                
                $sql =  "   UPDATE STP
                            SET STP_WFL_id ='$tempWorkflow', STP_order ='$seq'
                            WHERE STP_id ='$opID'";

                $result=mysqli_query($conn,$sql);

            } else if (strpos($op , "n")){

                //this is new operation, add it to the temporary user jobsheet
            
                $opID = str_replace("n","", $op);
                $opID = str_replace("opNum=","", $opID);

                $sql =  "SELECT  OPS_title, OPS_desc, OPS_detail
                            FROM	OPS
                            WHERE 	OPS_id = '$opID' AND (OPS_CUS_id = '$custID' OR OPS_CUS_id ='99')";

                $result = mysqli_query($conn,$sql);

                if ($result->num_rows != 0){

                    while($row = $result->fetch_assoc()){

                        $title = $row['OPS_title'];
                        $desc = $row['OPS_desc'];
                        $detail = $row['OPS_detail'];
                        $status = "Pending";

                        $stmt = $conn->prepare("INSERT INTO STP (STP_order, STP_status, STP_title, STP_desc, STP_WFL_id, STP_detail) VALUES (?,?,?,?,?,?)");
                        $stmt->bind_param("isssis", $seq, $status, $title, $desc, $tempWorkflow, $detail);

                        $stmt->execute();
                    }

                }

            } else {  $output = "Error identifying step.";  }

        } //end cycling through posted steps

        //clear the existing workflow
        $sql = "DELETE 
                FROM STP
                WHERE STP_WFL_id = '$wkfID'";

        $result=mysqli_query($conn,$sql);

        //assign steps from new workflow to existing workflow
        $sql = "UPDATE STP
                SET STP_WFL_id ='$wkfID'
                WHERE STP_WFL_id ='$tempWorkflow'";

        $result=mysqli_query($conn,$sql);

        //set first pending step to open, close all other open steps
        $sql = "SELECT STP_id, STP_status
                    FROM STP
                    WHERE STP_WFL_id = '$wkfID'
                    ORDER BY STP_order";

        $result=mysqli_query($conn,$sql);

        if ($result->num_rows != 0){

            $foundOpen = false;

            while($row = $result->fetch_assoc()){

                $thisRow = $row['STP_id'];
                $thisRowStatus = $row['STP_status'];

                if ($thisRowStatus == 'Pending' && !$foundOpen){
                    //This is first pending step, set it to open
                    $sql = "UPDATE STP
                            SET STP_status ='Open'
                            WHERE STP_id ='$thisRow'";

                        $resultB=mysqli_query($conn,$sql);

                    $foundOpen = true;

                } elseif ($thisRowStatus == 'Open' && !$foundOpen){
                    
                    //the first step is already open
                    $foundOpen = true;

                } elseif ($thisRowStatus == 'Open'){
                    //this is another open step, set it to pending
                    $sql = "UPDATE STP
                            SET STP_status ='Pending'
                            WHERE STP_id ='$thisRow'";

                    $resultC=mysqli_query($conn,$sql);

                }
            }
        }

        $output .= "Changes Saved.";

    } else {
        
            //no steps posted.  Verify empty then remove all
        if (isset($_POST['verify'])){

            if ($_POST['verify'] == '1'){

                //verified empty set posted, remove all steps

                $sql = "DELETE 
                FROM STP
                WHERE STP_WFL_id = '$wkfID'";

                $result=mysqli_query($conn,$sql);

                $output = "Changes Saved. No steps";

            }
        }
    }
}

echo $output;

?>