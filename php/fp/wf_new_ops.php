<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

//default declaration values

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

//All the available steps
$sql = "SELECT OPS_id, OPS_title, OPS_desc
FROM OPS
WHERE OPS_CUS_id = '$custID' OR OPS_CUS_id = '99'
ORDER By OPS_type, OPS_title";

$result = mysqli_query($conn,$sql);

if($result){

    $ops = "<div class='opsHead'>Available Steps</div>
            <div id='opsContainer' class='opsContainer'>
                <div class='opsSub'>Click or Drag to Workflow to Add --></div>
                <div id='sourceOps' class='connectedSortable'>";

    if($result->num_rows != 0){

    while($row = $result->fetch_assoc()){

        $newID =  $row['OPS_id'] . 'n';
        $newTitle = $row['OPS_title'];
        $newDesc = $row['OPS_desc'];

        $ops .= "<div class='s_panel availOp' id='opNum=$newID'>
                    <div class=''>
                        <div class='orderBox'></div>
                        <div class='titleBox'>$newTitle | $newDesc</div>
                    </div>
                </div>";
        }
    }

    $ops .= "</div></div>";

} else {
    $ops = "Bad query for available ops.";
}


$output = "<div class='contentTitle'>
                New Workflow
            </div>
            <div class='contentHolder'>
                <div id='workflowHeader' class='leftDiv'>
                    <div id='ops'>$ops</div>
                </div>
                <div id='accordionHolder'class='rightDiv scrollable'>
                <div class='opsHead'>Add Workflow Steps
                </div>
                
                <div class='opsSub'>Drag Steps to Change Order.  Drag Away to Delete.</div>
                    <div id='stepsUpdate'>
                    steps
                    </div>
                </div>
            </div>
            ";


echo $output;

?>