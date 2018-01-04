<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';

//DEBUG
$custID = '1';

// TEMPLATES SELECT
$templateSelect = "<div class='inputControl'>
                        <select class='inputField controlSelect' id='newFromTemp'>
                        <option disabled selected>Select Template</option>";

$sql = "SELECT DISTINCT WFL_num, WFL_id, WFL_item
        FROM WFL
        WHERE WFL_CUS_id = '$custID' AND WFL_status = 'Template'
        ORDER BY WFL_num";

$result = mysqli_query($conn,$sql);

if($result){
    
    if($result->num_rows != 0){

        while($row = $result->fetch_assoc()){

            $num = $row['WFL_num'];
            $id = $row['WFL_id'];
            $item = $row['WFL_item'];

            $templateSelect .= "<option value ='$id'>$num | $item</option>";
        }

    }
}

$templateSelect .= "</select></div>";

// EXISTING SELECT
$existingSelect = "<div class='inputControl'>
                    <select class='inputField controlSelect' id='newFromExist'>
                    <option disabled selected>Select Workflow</option>";

$sql = "SELECT DISTINCT WFL_num, WFL_id, WFL_item
            FROM WFL
            WHERE WFL_CUS_id = '$custID' AND WFL_status <> 'Template'
            ORDER BY WFL_num";

$result = mysqli_query($conn,$sql);

if($result){

    if($result->num_rows != 0){

        while($row = $result->fetch_assoc()){

            $num = $row['WFL_num'];
            $id = $row['WFL_id'];
            $item = $row['WFL_item'];

            $existingSelect .= "<option value ='$id'>$num | $item</option>";
        }

    }
}

$existingSelect .= "</select></div>";

$output = "
    <div id='activeItem' class='accd_header_active' onclick='hActive()'>
            Create a New Workflow
        </div>
        <div id='activeContent' class='accd_content'>
            <div id='radio-wf-item' class='selectRadio'>Use a Workflow Template</div>
            $templateSelect
            <div class='alignRight'>
                <input type='button' onclick='fromExistTemp()' class='button buttonBlue' value='Create'>
            </div>
            <div id='radio-wf-group' class='selectRadio'>Copy an Existing Workflow</div>
            $existingSelect
            <div class='alignRight'>
                <input type='button' onclick='fromExistWf()' class='button buttonBlue' value='Create'>
            </div>
            <hr>
            <div id='radio-wf-active' onclick='fromScratch()'' class='selectRadioNew'>+ Create from Scratch</div>  
        </div>";

echo $output;
        

?> 