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

$statusSelect = "<select id='usPerm' class='textTableInput' onchange='usPermChange(this)'>
                            <option value='0'>Inactive</option>
                            <option value='10' selected>Standard User</option>
                            <option value='20'>Power User</option>  
                            <option value='30'>Administrator</option>                  
                        </select>
                        <input type='hidden' id='usPermStore' value='10'>";

$output = "
            <div id='activeItem' class='accd_header_active' onclick='hActive()'>
                    Add a New User
                </div>
                <div id='activeContent' class='accd_content' hidden>
                    <div id='usContainer' class='container'>
                        <div class='labelDiv'>Name</div>
                        <div class='dataInputDiv'>
                            <input id='usName' value='' class='textTableInput'></div>
                        <div class='labelDiv'>email</div>
                        <div class='dataInputDiv'>
                            <input id='usMail' value='' class='textTableInput'></div>
                        <div class='labelDiv'>phone</div>
                        <div class='dataInputDiv'>
                            <input id='usPhone' value='' class='textTableInput'></div>
                        <div class='labelDiv'>Access Level</div>
                        <div class='dataInputDiv'>$statusSelect</div>                        
                        <div class='scaleText1 alignRight'>
                            <input type='button' class='button buttonGreen editH1' onclick='saveNewUser()' value='Save' hidden>
                            <input type='button' class='button buttonGray editH1' onclick='h4()' value='Cancel'>
                        </div>
                    </div>
                </div>";


echo $output;
?>