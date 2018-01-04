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

if (isset($_POST['wfl_id'])){

    $wfID = $_POST['wfl_id'];
    
    //Build select filter
    $sql = "SELECT DISTINCT OPS_type
                FROM OPS
                WHERE OPS_CUS_id = '$custID' OR OPS_CUS_id = '99'
                ORDER By OPS_type";

    $result = mysqli_query($conn,$sql);

    if($result){

        $opFilter = "<select id='filterOps' class='controlSelect'>
                        <option value = '%'>All Available Steps</option>";

        if($result->num_rows != 0){

            while($row = $result->fetch_assoc()){

                $type =  $row['OPS_type'];

                $opFilter .= "<option value='$type'>$type</option>";
            }

            $opFilter .= "</select>";
        }
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
                    <div>
                        $opFilter
                    </div>
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


    //Query Workflow
    $sql = "SELECT WFL_id, WFL_item, WFL_num, WFL_desc, WFL_status, WFL_ref, WFL_group
    FROM WFL
    WHERE WFL_CUS_id = '$custID' AND WFL_id = '$wfID'";
        
    $result = mysqli_query($conn,$sql);

    if($result){

        if($result->num_rows != 0){

            while($row = $result->fetch_assoc()){

                $wfNumber = "Modify Workflow " . $row['WFL_num'];

            }
        }
    } 

$output = "<div class='contentTitle'>
                $wfNumber
            </div>
            <div class='contentHolder'>
                <div id='workflowHeader' class='leftDiv'>
                    <div id='ops'>$ops</div>
                </div>
                <div id='accordionHolder'class='rightDiv'>
                <div class='opsHead'>Current Workflow Steps
                    <div class='right '>
                        <input type='button' id='wfBtnSave' class='button buttonGreen scaleText2' onclick='saveWf()' value='Save'>
                        <input type='button' id='wfBtnUndo' class='button buttonGray scaleText2' onclick='getModSteps($wfID)' value='Undo'>
                    </div>
                </div>
                <div class='opsSub'>Drag Steps to Change Order.  Drag Away to Delete.</div>
                <div id='accordianScroll' class='container scrollable'>
                        error loading steps...please retry
                    </div>
                </div>
            </div>
            ";

} else {
    $output = "Error posting data";
}
echo $output;

?>