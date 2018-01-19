<?php

//linked files
require_once 'dbConnect.php';

$code = $hash = "";

if (isset($_GET['email']) && isset($_GET['t'])){

    $email = $_GET['email'];
    $token = $_GET['t'];

    //check email and token
    $sql = "SELECT USR_id, USR_hash, USR_ver
    FROM USR
    WHERE USR_email = '$email'";

    $result=mysqli_query($conn,$sql);

    //confirm this is a additional user account for verification

    if($result->num_rows == 0){
        
        $code .= '1'; //1 email not found

    } else {

        $row = $result->fetch_assoc();

        $usrVer = $row['USR_ver'];
        $hash = $row['USR_hash'];
        

        if ($usrVer == 1){

            $code .='2'; //2 already verified

        } else if ($usrVer == 3 && $hash == $token) {

            $code .= '0'; //verified, unlock & send for password se


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

if ($code == '0'){
    header( "Location: http://localhost/workflowlab_c/php/passwordSet.php?t=$hash&e=$email");

} else {
    header( "Location: http://localhost/workflowlab_c/php/login.php?c=$code&e=$email&t=$hash");
}


?>