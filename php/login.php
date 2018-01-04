<?php
//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'fp/dbConnect.php';

//variable definitions
$submittedPassword = $password = $code = $email = $message = $visible ="";

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
    if (isset($_POST['email'])){

        $email = $_POST["email"];
        $submittedPassword = $_POST["password"];

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        $sql = "SELECT USR_key, USR_id, USR_CUS_id, USR_name, USR_perm, USR_ver
                FROM USR
                WHERE USR_email = '$email'";
        
        if ($result=mysqli_query($conn,$sql)){

        mysqli_data_seek($result,0);

        // Fetch row
        $row=mysqli_fetch_row($result);

        $password = $row[0];

        if(password_verify($submittedPassword, $password)){

                $verCode = $row[5];

                //TODO check to make sure this account has been verified.  If not spit out reverify message.  Send email if req.
                if($verCode == 1){

                        //user account has been verified.                        
                        $_SESSION['userID'] = $row[1];
                        $_SESSION['custID'] = $row[2];
                        $_SESSION['userName'] = $row[3];
                        $_SESSION['userPerm'] = $row[4];
                        
                        header( "Location: workflowLab.php");
                } else {

                    $message = "This account has not been verified.<br><br>
                                Check your email for the verification link or<br><br>
                                <input type='button' onclick='resendVerification()' class='button buttonBlue' value='Resend verification email.' />";

                }

            } else {

                $message = "Email / Password combination not found.<br><br>
                <input type='button' onclick='resetPassword()' class='button buttonBlue' id='resetButton' value='Click to Reset Password' />";
            }

        } else {

        $message = "Email / Password combination not found.<br><br>
        < input type='button' onclick='resetPassword()' class='button buttonBlue' id='resetButton' value='Click to Reset Password' />";

        }
        
    }

} else if ( $_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET["c"])){

    $code = $_GET["c"];
    $email = $_GET["e"];

    switch ($code){

        case 0:

            $message = "Account verified!<br><br>
                        Please log in.";
            break;

        case 1:

            $message = "Account not found.<br><br>
                        <input type='button' onclick='resendVerification()' class='button buttonBlue' value='Resend verification email.' />";
            break;

        case 2:

            $message = "Account verified!<br><br>
                        Please log in.";
            break;

        case 3:

            $message = "We could not verify your email.<br><br>
                        Please try the verification link again or<br><br>
                        <input type='button' onclick='resendVerification()' class='button buttonBlue' value='Resend verification email.' />";

            break;

        default:

        $message = "";
    }


}

echo <<<_FixedHTML

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="../css/index.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
    <title>Log In</title>

    <script>

        function resendVerification(){

            var email = document.getElementById("email").value;

            if (!email){

                $('#email').css("background-color", "#F9F9A7");
                $("#loginRight").hide().fadeIn("slow").html("Enter the email to verify.");

            } else {

                $("#workingDiv").fadeIn("slow");

                $.ajax({
                    type: 'POST',
                    url: 'fp/reVerify.php',   
                    dataType: 'html',
                    data: {
                        ver_email : email
                    },
                    success: function (html) {
                        $("#loginRight").hide().fadeIn("slow").html(html);
                        $("#workingDiv").fadeOut("slow");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                        $("#workingDiv").fadeOut("slow");
                    }
                });

            }

        }

        function resetPassword(){
            
            var email = document.getElementById("email").value;

            if (!email){

                $('#email').css("background-color", "#F9F9A7");
                $("#loginRight").hide()
                    .fadeIn("slow")
                    .html("Enter the email to to reset the password.<br><br><input type='button' onclick='resetPassword()' class='button buttonBlue' id='resetButton' value='Click to Reset Password' />");

            } else {

                $("#workingDiv").fadeIn("slow");

                $.ajax({
                    type: 'POST',
                    url: 'fp/resetPassword.php',   
                    dataType: 'html',
                    data: {
                        ver_email : email
                    },
                    success: function (html) {
                        $("#loginRight").hide().fadeIn("slow").html(html);
                        $("#workingDiv").fadeOut("slow");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        alert("Status: " + textStatus); alert("Error: " + errorThrown); 
                        $("#workingDiv").fadeOut("slow");
                    }
                });

            }

        }
            

    </script>
</head>
<body>  
    <div class='content'>
        <div class='titleDiv'>
            <img src='../media/logo6.png' class='logo'>

            </div>
        </div>
        <div id='login'>
            <div id='registrationLeft' class='left'>
            <form method='post' action='login.php'>
            <table>
                <thead>
                    <tr>
                        <th colspan='2'>Log-In to Workflow Lab</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>email</td>
                        <td><input type="email" name='email' required value='$email' id='email'></td>
                    </tr>
                    <tr>
                        <td>password</td>
                        <td><input type="password" name='password' required></td>
                    </tr>
                    <tr>
                        <td></td><td><input type="submit" value='Log In' class='button buttonBlue'></td>
                    </tr>
                </tbody>
            </table>
            </form>
            </div>
            <div id='loginRight' $visible>$message</div>
        </div>
    </div>
    <div id='workingDiv'>
        <div id='workingDivText'>working...
        </div>
    </div>

    <script>
        $("#workingDiv").hide();
    </script>
</body>
</html>

_FixedHTML;


?>