<?php

//linked files
require_once 'dbConnect.php';

$code = $existingVer = "";

if (isset($_GET['email']) && isset($_GET['t'])){

    $email = $_GET['email'];
    $token = $_GET['t'];

    //check email and token
    $sql = "SELECT USR_id, USR_hash
    FROM USR
    WHERE USR_email = '$email'";

    $result=mysqli_query($conn,$sql);

    //String validation for input

    if($result->num_rows == 0){
        
        $code .= '1'; //1 email not found

    } else {

        $row = $result->fetch_assoc();

        $existingVer = $row['USR_hash'];

        if ($existingVer == 1){

            $code .='2'; //2 already verified

        } else if ($existingVer == $token) {

            $code .= '0'; //verified, unlock

            $sql =  "UPDATE USR
                        SET USR_ver ='1'
                        WHERE USR_email = '$email'";

$result=mysqli_query($conn,$sql);


        } else {

            $code .= '3'; //token doesn't match

        }

    }

} else {

    $code .= '4'; //get not set correctly

}

header( "Location: http://localhost/workflowlab_beta/php/login.php?c=$code&e=$email");

?>