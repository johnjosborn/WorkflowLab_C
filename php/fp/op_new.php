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


$statusSelect = "<select id='opSta' class='textTableInput' onchange='opStatusChange(this)'>
                <option value='Active' selected>Active</option>
                <option value='Inactive'>Inactive</option>                
            </select>
            <input type='hidden' id='opStaStore' value='Active'>";

$output = "
<div id='activeItem' class='accd_header_active' onclick='hActive()'>
        Create a New Step
    </div>
    <div id='activeContent' class='accd_content' hidden>
        <div id='opContainer' class='container'>
            <div class='labelDiv'>Title</div>
            <div class='dataInputDiv'>
                <input id='opTitle' value='' class='textTableInput'></div>
            <div class='labelDiv'>Descritpion</div>
            <div class='dataInputDiv'>
                <input id='opDesc' value='' class='textTableInput'></div>
            <div class='labelDiv'>Details</div>
            <div class='dataInputDiv'>
                <input id='opDetail' value='' class='textTableInput'></div>
            <div class='labelDiv'>Type</div>
            <div class='dataInputDiv'>
                <input id='opType' value='' class='textTableInput'></div>   
            <div class='labelDiv'>Status</div>
            <div class='dataInputDiv'>$statusSelect</div>
            <div class='scaleText1 alignRight'>
                <input type='button' class='button buttonGreen editH1' onclick='saveNewOp()' value='Save'>
                <input type='button' class='button buttonGray editH1' onclick='h3()' value='Cancel'>
            </div>
        </div>
    </div>";

echo $output;
?>