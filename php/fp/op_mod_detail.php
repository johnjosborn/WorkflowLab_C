<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';
//require_once 'logInValidation.php'; //$userID, $custID

//DEBUG
$custID = '1';

$output = "initial";

// Check connection
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if (isset($_POST['op_id'])){

    $output = "posted";
    
    $opID = $_POST['op_id'];

    //Query Workflow

    $sql = "SELECT OPS_id, OPS_title, OPS_desc, OPS_detail, OPS_type, OPS_status
            FROM OPS
            WHERE OPS_CUS_id = '$custID' AND OPS_id = '$opID'";
        
    $result = mysqli_query($conn,$sql);

    if($result){

        
        if($result->num_rows != 0){
            
            while($row = $result->fetch_assoc()){

                $opTitle = $row['OPS_title'];
                $opDesc = $row['OPS_desc'];
                $opDetail = $row['OPS_detail'];
                $opType = $row['OPS_type'];
                $opSta = $row['OPS_status'];

            }

            $opStaA = "";
            $opStaI = "";
        

            switch ($opSta){
                case "Active":
                $opStaA = "selected ='selected'";
                break;
                case "Inactive":
                $opStaI = "selected ='selected'";
                break;

            }

            $statusSelect = "<select id='opSta' class='textTableInput' onchange='opStatusChange(this)'>
                            <option value='Active' $opStaA>Active</option>
                            <option value='Inactive' $opStaI>Inactive</option>                
                        </select>
                        <input type='hidden' id='opStaStore' value='$opSta'>";

            $output = "
            <div id='activeItem' class='accd_header_active' onclick='hActive()'>
                    Step Details: $opTitle
                </div>
                <div id='activeContent' class='accd_content' hidden>
                    <div id='opContainer' class='container'>
                        <div class='labelDiv'>Title</div>
                        <div class='dataInputDiv'>
                            <input id='opTitle' value='$opTitle' class='textTableInput'></div>
                        <div class='labelDiv'>Descritpion</div>
                        <div class='dataInputDiv'>
                            <input id='opDesc' value='$opDesc' class='textTableInput'></div>
                        <div class='labelDiv'>Details</div>
                        <div class='dataInputDiv'>
                            <input id='opDetail' value='$opDetail' class='textTableInput'></div>
                        <div class='labelDiv'>Type</div>
                        <div class='dataInputDiv'>
                            <input id='opType' value='$opType' class='textTableInput'></div>   
                        <div class='labelDiv'>Status</div>
                        <div class='dataInputDiv'>$statusSelect</div>
                        <div class='scaleText1 alignRight'>
                            <input type='button' class='button buttonGreen editH1' onclick='saveOpDetail($opID)' value='Save' hidden>
                            <input type='button' class='button buttonGray editH1' onclick='editOp($opID)' value='Undo' hidden>
                            <input type='button' class='button buttonRed editH2' onclick='deleteOp($opID)' value='DELETE'>
                        </div>
                        <input type='hidden' id='opID' value='$opID'>
                    </div>
                </div>";


        } else { $output = "Step not found.";}

    } else { $output = "Step not found.";}

} else { $output = "Data not sent."; }

echo $output;
?>