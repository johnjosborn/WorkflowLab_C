<?php

$userID = "default";

if (isset($_SESSION['userID'])){
    $userID =  $_SESSION['userID'];
    $custID = $_SESSION['custID'];
    $userName = $_SESSION['userName'];
    $userPerm = $_SESSION['userPerm'];

} else {
    header( "Location: http://localhost/workflowlab/");
}

?>