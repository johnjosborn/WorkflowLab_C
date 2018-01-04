<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

$workFlowHeader = "initial";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['wfl_id'])){

    
    $wfID = $_POST['wfl_id'];

    //Query Workflow

    $sql = "SELECT WFL_id, WFL_item, WFL_num, WFL_desc, WFL_status, WFL_ref, WFL_group, WFL_notes
            FROM WFL
            WHERE WFL_CUS_id = '$custID' AND WFL_id = '$wfID'";
        
    $result = mysqli_query($conn,$sql);

    if($result){

        if($result->num_rows != 0){

            while($row = $result->fetch_assoc()){

                $wfNumber = $row['WFL_num'];
                $wfItem =  $row['WFL_item'];
                $wfDesc = $row['WFL_desc'];
                $wfSta = $row['WFL_status'];
                $wfRef = $row['WFL_ref'];
                $wfGrp = $row['WFL_group'];
                $wfNot = $row['WFL_notes'];
            

                $wfStaC = "";
                $wfStaA = "";
                $wfStaP = "";
                $wfStaX = "";

                switch ($wfSta){
                    case "Complete":
                    $wfStaC = "selected ='selected'";
                    break;
                    case "Active":
                    $wfStaA = "selected ='selected'";
                    break;
                    case "Pending":
                    $wfStaP = "selected ='selected'";
                    break;
                    case "Cancelled":
                    $wfStaX = "selected ='selected'";
                    break;

                }

            }

        }

}

    $statusSelect = "<select id='wfSta' class='textTableInput' onchange='statusChange(this)'>
            <option value='Active' $wfStaA>Active</option>
            <option value='Complete' $wfStaC>Complete</option>
            <option value='Pending' $wfStaP>Pending</option>
            <option value='Cancelled' $wfStaX>Cancelled</option>                
        </select>
        <input type='hidden' id='staStore' value='$wfSta'>";

    //Workflow header
    $workFlowHeader = "
        <div id='wfContainer' class='container'>
            <div class='labelDiv'>Number</div>
            <div class='dataInputDiv'>
                <input id='wfNum' value='$wfNumber' class='textTableInput'></div>
            <div class='labelDiv'>Item</div>
            <div class='dataInputDiv'>
                <input id='wfItem' value='$wfItem' class='textTableInput'></div>
            <div class='labelDiv'>Description</div>
            <div class='dataInputDiv'>
                <input id='wfDesc' value='$wfDesc' class='textTableInput'></div>
            <div class='labelDiv'>Reference</div>
            <div class='dataInputDiv'>
                <input id='wfRef' value='$wfRef' class='textTableInput'></div>   
            <div class='labelDiv'>Group</div>
            <div class='dataInputDiv'>
                <input id='wfGrp' value='$wfGrp' class='textTableInput'></div>      
            <div class='labelDiv'>Notes</div>
            <div class='dataInputDiv'>
                <input id='wfNot' value='$wfNot' class='textTableInput'></div> 
            <div class='labelDiv'>Status</div>
            <div class='dataInputDiv'>$statusSelect</div>
            <div class='scaleText1 alignRight'>
                <input type='button' class='button buttonGreen editH1' onclick='saveWfHeader($wfID)' value='Save' hidden>
                <input type='button' class='button buttonGray editH1' onclick='getModHeader($wfID)' value='Undo' hidden>
                <input type='button' id='wfBtnReset' class='button buttonBlue editH2' onclick='resetWf()' value='Reset to Beginning'>
                <input type='button' class='button buttonRed editH2' onclick='deleteWf($wfID)' value='DELETE'>
                <input type='button' class='button buttonBlue editH2' onclick='cancelEdit($wfID)' value='Exit Edit Mode'>
            </div>
            <input type='hidden' id='wfID' value='$wfID'>
        </div>
        ";

    echo $workFlowHeader;

} else { $workFlowHeader = "Bad query"; }

?>