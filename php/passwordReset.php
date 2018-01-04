<?php

//linked files
require_once 'fp/dbConnect.php';

$message = "";

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
        
        $message = "<table>
        <thead>
            <tr>
                <th colspan='2'>Re-Set Password</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>We could not find an account for $email.</td>
            </tr>
            <tr>
                <td>Try the email link again or</td>
            </tr>
            <tr>
                <td>contact us for help.</td>
            </tr>
        </tbody>
    </table>";

    } else {

        $row = $result->fetch_assoc();

        $existingVer = $row['USR_hash'];

        if ($existingVer == $token) {

            $message = "<table>
                        <thead>
                            <tr>
                                <th colspan='2'>Re-Set Password</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>email</td>
                                <td><input type='email' id='email' class='regInput' value='$email' readonly></td>
                            </tr>
                            <tr>
                                <td>New Password</td>
                                <td><input type='password' id='password' class='regInput'></td>
                            </tr>
                            <tr>
                                <td>Confirm Password</td>
                                <td><input type='password' id='password2' class='regInput'></td>
                            </tr>
                            <tr>
                                <td><input type='hidden' id='token' value='$token'></td>
                                <td><input type='button' onclick='changePassword()' class='button buttonBlue' value='Reset Password' /></td><td></td>
                            </tr>
                        </tbody>
                    </table>";

        } else {

            $message = "<table>
                            <thead>
                                <tr>
                                    <th colspan='2'>Re-Set Password</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>There was a problem confirming this password reset request for this email.</td>
                                </tr>
                                <tr>
                                    <td><input type='email' id='email' class='regInput' value='$email' readonly></td>
                                </tr>
                                <tr>
                                    <td>Try the email link again or</td>
                                </tr>
                                <tr>
                                    <td><input type='button' onclick='resetPassword()' class='button buttonBlue' id='resetButton' value='Click to Resend email' /></td>
                                </tr>
                            </tbody>
                        </table>";
        }

    }

} else {

    echo "Get all f'd";

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
    <title>Password Re-Set</title>

    <script>

        function resetPassword(){

            
            var email = document.getElementById("email").value;
            
            if (!email){

                $('#email').css("background-color", "#F9F9A7");
                $("#loginRight").hide().fadeIn("slow").html("Enter the email to to reset the password.<br><br><button onclick='resetPassword()' class='button buttonBlue' id='resetButton'>Click to Reset Password</button>");

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
                        $("#loginLeft").hide()
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

        function changePassword(){
            
                        
                        var email = document.getElementById("email").value;
                        var token = document.getElementById("token").value;
                        var pass1 = $('#password').val();
                        var pass2 = $('#password2').val();

                        var verified = true;
                        
                        var failMessage = '';
                        
                        if (!email){
                            
                            failMessage = failMessage + "An email is required.<br>";
                            
                            $('#email').css("background-color", "#F9F9A7");
                            $('#email').css("color", "red");
                            verified = false;
                        }
            
                        if (!pass1){
            
                            failMessage = failMessage + "An password is required.<br>";
                            
                            $('#password').css("background-color", "#F9F9A7");
                            $('#password2').css("background-color", "#F9F9A7");
                            $('#password').css("color", "red");
                            verified = false;
                        } 
                        
                        if (pass1 != pass2){
            
                            failMessage = failMessage + "Passwords do not match.<br>";
                            $('#password').css("background-color", "#F9F9A7");
                            $('#password2').css("background-color", "#F9F9A7");
                            $('#password').css("color", "red");
                            $('#password2').css("color", "red");
                            verified = false;
            
                        } 
            
                        if (verified){
            
                            $("#workingDiv").fadeIn("slow");
            
                            $.ajax({
                                type: 'POST',
                                url: 'fp/changePassword.php',   
                                dataType: 'html',
                                data: {
                                    new_token : token,
                                    new_email : email,
                                    new_pass1 : pass1,
                                    new_pass2 : pass2
                                },
                                success: function (html) {
                                    switch(html) {
                                        case '0':
                                            var info = "Error sending post info.";
                                            break;
                                        case '1':
                                            var info = "We could not find this email.";
                                            break;
                                        case '2':
                                            var info = "We could not find this verify this email.";
                                            break;
                                        case '3':
                                            var info = "Password updated.  Go <a href='login.php'>here</a> to sign in.";
                                        break;
                                        default:
                                            var info = html;
                                    }
                                    $("#loginLeft").hide();
                                    $("#loginRight").hide().fadeIn("slow").html(info);
                                    $("#workingDiv").fadeOut("slow");
                                },
                                error: function(XMLHttpRequest, textStatus, errorThrown) { 
                                    alert("Status: " + textStatus); alert("Error: " + errorThrown);
                                    $("#workingDiv").fadeOut("slow"); 
                                }
                            });
            
                        } else {
            
                            $("#loginRight").hide().fadeIn("slow").html(failMessage);
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
            <div id='loginLeft' class='left'>
                $message
            </div>
            <div id='loginRight'></div>
        </div>
    </div>
    <div id='workingDiv'>
        <div id='workingDivText'>working...
        </div>
    </div>

    <script>
        $("#workingDiv").hide();
        $("#loginRight").hide();
    </script>
</body>
</html>

_FixedHTML;



?>