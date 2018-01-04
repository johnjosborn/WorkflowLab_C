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
if (!$conn) { die("Connection failed: " . mysqli_connect_error());}

        //this is a new workflow
        $wfStaC = "";
        $wfStaA = "";
        $wfStaP = "";
        $wfStaX = "";

        $wfNumber = "";
        $wfItem =  "";
        $wfDesc = "";
        $wfSta = "Active";
        $wfRef = "";
        $wfGrp = "";
        $wfNot = "";
        $wfStaA = "Selected";



    $statusSelect = "<select id='wfSta' class='textTableInput' onchange='statusChange(this)'>
                        <option value='Active' selected>Active</option>
                        <option value='Complete'>Complete</option>
                        <option value='Pending'>Pending</option>
                        <option value='Cancelled'>Cancelled</option>                
                    </select>
                    <input type='hidden' id='staStore' value='$wfSta'>";

    //Workflow header
    $workFlowHeader = "
        <div id='wfContainer' class='container'>
            <div class='labelDiv'>Number</div>
            <div class='dataInputDiv'>
                <input id='wfNum' value='' class='textTableInput' placeholder='required'></div>
            <div class='labelDiv'>Item</div>
            <div class='dataInputDiv'>
                <input id='wfItem' value='' class='textTableInput'></div>
            <div class='labelDiv'>Description</div>
            <div class='dataInputDiv'>
                <input id='wfDesc' value='' class='textTableInput'></div>
            <div class='labelDiv'>Reference</div>
            <div class='dataInputDiv'>
                <input id='wfRef' value='' class='textTableInput'></div>   
            <div class='labelDiv'>Group</div>
            <div class='dataInputDiv'>
                <input id='wfGrp' value='' class='textTableInput'></div>      
            <div class='labelDiv'>Notes</div>
            <div class='dataInputDiv'>
                <input id='wfNot' value='' class='textTableInput'></div> 
            <div class='labelDiv'>Status</div>
            <div class='dataInputDiv'>$statusSelect</div>
            <div class='scaleText1 alignRight'>
                <input type='button' class='button buttonRed editH' onclick='saveNewWf()' value='Save'>
                <input type='button' class='button buttonBlue editH' onclick='h1()' value='Cancel'>
            </div>
            <input type='hidden' id='wfID' value='0'>
        </div>
        ";

    echo $workFlowHeader;

?>