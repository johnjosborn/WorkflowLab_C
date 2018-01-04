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

    $activeWkf = $_POST['wfl_id'];

    //All the steps
    $sql = "SELECT STP_id, STP_title, STP_desc, STP_order, STP_status, STP_note, STP_detail, STP_USR_id, STP_date
            FROM STP
            WHERE STP_WFL_id = '$activeWkf'
            ORDER By STP_order";
            
    $result = mysqli_query($conn,$sql);

    if($result){
    
        $steps = "<div id='stepAccordian' class='connectedSortable'>";

        if($result->num_rows != 0){

            while($row = $result->fetch_assoc()){

                $thisStatus = $row['STP_status'];

                switch($thisStatus){

                    case "Open":
                        $class = "stepOpen";
                    break;

                    case "Pending":
                        $class = "stepPending";
                    break;

                    case "Complete":
                        $class = "stepComplete";
                    break;
                }

                $stpID = $row['STP_id'] . 'x';
                $stpIDBase = $row['STP_id'];
                $stpTitle = $row['STP_title'];
                $stpDesc = $row['STP_desc'];
                $stpDetails = $row['STP_detail'];
                $stpOrder = $row['STP_order'];
                $stpUsr = $row['STP_USR_id'];
                $stpDate = $row['STP_date'];
                $stpNotes = $row['STP_note'];

                $users = "";
                
                //get list of users
                $sqlUsr = "SELECT USR_id, USR_name
                        FROM USR
                        WHERE USR_CUS_id = '$custID'
                        ORDER By USR_name";

                $resultUsr = mysqli_query($conn,$sqlUsr);

                if($resultUsr){
                
                    $users = "<select id='$stpIDBase' onchange='userChange(this)' class='textTableInput stepInput'>";

                    if ($stpUsr == 0){
                        $users .= "<option disabled selected value='0'>Select User</option>";
                    }

                    if($resultUsr->num_rows != 0){

                        while($rowUsr = $resultUsr->fetch_assoc()){

                            if ($rowUsr['USR_id'] == $stpUsr){
                                $users .= "<option value='" .$rowUsr['USR_id'] . "' selected>" .$rowUsr['USR_name'] . "</option>";
                            } else {
                                $users .= "<option value='" .$rowUsr['USR_id'] . "'>" .$rowUsr['USR_name'] . "</option>";
                            }

                        }

                        $users .= "</select>";

                    }

                } else {
                    $users = "No users found.";
                }


                $steps .= "<div class='s_panel' id='$stpID'>
                <div class='stepHeader $class'>
                    <div class='orderBox'>$stpOrder</div>
                    <div class='titleBox'>$stpTitle</div>
                </div>
                <div class='stepDetail'>
                    <div class='labelDiv'>Title</div>
                    <div class='dataInputDiv'>
                        <input id='stpTitle$stpIDBase' value='$stpTitle' class='textTableInput stepInput'>
                    </div>
                    <div class='labelDiv'>Description</div>
                    <div class='dataInputDiv'>
                        <input id='stpDesc$stpIDBase' value='$stpDesc' class='textTableInput stepInput'>
                    </div>
                    <div class='labelDiv'>Details</div>
                    <div class='dataInputDiv'>
                        <input id='stpDetail$stpIDBase' value='$stpDetails' class='textTableInput stepInput'>
                    </div>
                    <div class='labelDiv'>Notes</div>
                    <div class='dataInputDiv'>
                        <input id='stpNotes$stpIDBase' value='$stpNotes' class='textTableInput stepInput'>
                    </div>
                    <div class='labelDiv'>Completed By:</div>
                    <div class='dataInputDiv'>
                        $users   
                        <input type='hidden' id='userStore$stpIDBase' value='$stpUsr'>
                    </div>
                    <div class='labelDiv'>Completed Date:</div>
                    <div class='dataInputDiv'>
                        <input type='text' id='stpDate$stpIDBase' value='$stpDate' class='datePicker textTableInput stepInput'>
                    </div>";

        if ($thisStatus == "Complete"){

        $steps .= "<button class='button buttonGray' onclick='resetStep($stpIDBase)'>Re-Set</button>";

        }

        $steps .= " <div id='stepEditButtons$stpID' class='headerItems stepButtons' hidden>
                        <button class='button buttonGreen' onclick='saveEditedStep($stpIDBase)'>Save Changes</button>
                        <button class='button buttonGray' onclick='getModSteps($stpIDBase)'>Undo Changes</button>
                    </div>
                    </div></div>";

        }

    }

    $steps .= "";

    } else { $steps = "Bad query"; }

}

echo $steps;