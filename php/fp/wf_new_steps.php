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

 $steps = "<div id='stepAccordian' class='connectedSortable'></div>";

echo $steps;