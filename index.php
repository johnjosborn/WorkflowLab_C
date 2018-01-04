<?php

$useCSS = "index.css";

echo <<<_FixedHTML

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" type="text/css" href="css/$useCSS">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Open+Sans+Condensed:300" rel="stylesheet">
    <title>Workflow Lab</title>
    <script>

        function register(){

            var cust = $('#cust').val();
            var user = $('#user').val();
            var email = $('#email').val();
            var pass1 = $('#password').val();
            var pass2 = $('#password2').val();
            
            var verified = true;
            
            var failMessage = '';
            
            $('.regInput').css("background-color", "#80FD80");
            $('.regInput').css("color", "black");
            
            if (cust == null || cust == ""){
                
                failMessage = failMessage + "Customer name is required.<br>";
                
                $('#cust').css("background-color", "#F9F9A7");
                $('#cust').css("color", "red");
                verified = false;
            } 

            if (!user){

                failMessage = failMessage + "A user name is required.<br>";
                
                $('#user').css("background-color", "#F9F9A7");
                $('#user').css("color", "red");
                verified = false;
            }

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
                    url: 'php/fp/register.php',   
                    dataType: 'html',
                    data: {
                        new_cust : cust,
                        new_user : user,
                        new_email : email,
                        new_pass1 : pass1,
                        new_pass2 : pass2
                    },
                    success: function (html) {
                        $("#registrationRight").hide().fadeIn("slow").html(html);
                        $("#workingDiv").fadeOut("slow");
                    },
                    error: function(XMLHttpRequest, textStatus, errorThrown) { 
                        alert("Status: " + textStatus); alert("Error: " + errorThrown);
                        $("#workingDiv").fadeOut("slow"); 
                    }
                });

            } else {

                $("#registrationRight").hide().fadeIn("slow").html(failMessage);
            }
        }

        function goToRegister() {
            $('html, body').animate({
                scrollTop: $("#registration").offset().top
            }, 2000);
        }

    </script>
</head>
<body>
    <div class='content'>
        <div class='titleDiv'>
            <img src='media/logo6.png' class='logo'>
            <div class='login'>
                <form method='post' action='php/login.php'>
                email <input type="email" name='email' required>
                password <input type="password" name='password' required>
                <input type="submit" onclick="logIn()" value='Log In' class='button1'>
                </form>
            </div>
            <div class='menuItems'>
                <table class='menuTable'>
                    <tr>
                        <td>
                            Getting Started
                        </td>
                        <td>
                            What This Is
                        </td>
                        <td>
                            Quick Tour
                        </td>
                        <td>
                            <div class='regLink' onClick='goToRegister()'>Sign Up Free</div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
        <div class='midDiv'>
            <img src='media/logo8.png' class='logo2'>
            The Efficient & Affordable ERP/MRP Alternative
            <br>
            for Small Businesses and Entreprenures.
        </div>
        <div class='actionDiv'>
            <table class='actionTable'>
            <tr>
                <td>
                    <div class='left lower'>
                        <p class='title'>Coordinate</p>
                        <img src='media/staff.png' class='logo'>
                        <p class='details'>Create and Link Accounts for all your staff.<br>Keep everyone working on the same page.</p>
                    </div>
                </td>
                <td>
                    <div class='center lower'>
                        <p class='title'>Track</p>
                        <img src='media/list.png' class='logo'>
                        <p class='details'>Define, Track, Manage and record<br>your operations.</p>
                    </div>
                </td>
                <td>
                    <div class='right lower'>
                        <p class='title'>Plan</p>
                        <img src='media/reports.png' class='logo'>
                        <p class='details'>Review and evalaute data to
                            <br>make informaed decisions about your business.</p>
                    </div>
                </td>
            </tr>
            </table>
        </div>
        <div id='registration'>
            <div id='registrationLeft' class='left'>
                <table>
                    <thead>
                        <tr>
                            <th colspan='2'>Sign Up for Workflow Labs</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Company Name</td>
                            <td><input type="text" id="cust" class='regInput'></td>
                            <td>
                                <div class="img__wrap" id='helpCompany'>
                                <img class="helpImage" src="media/help.png"/>
                                </div>
                                <div id='emailMessage' class='message'</div><div id='custMessage' class='message'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>Name</td>
                            <td><input type="text" id="user" class='regInput'></td>
                            <td>
                                <div class="img__wrap" id='helpName'>
                                <img class="helpImage" src="media/help.png"/>
                                </div>
                                <div id='emailMessage' class='message'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>email</td>
                            <td><input type="email" id="email" class='regInput'></td>
                            <td>
                                <div class="img__wrap" id='helpEmail'>
                                <img class="helpImage" src="media/help.png"/>
                                </div>
                                <div id='emailMessage' class='message'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>Password</td>
                            <td><input type="password" id="password" class='regInput'></td>
                            <td>
                                <div class="img__wrap" id='helpPassword'>
                                    <img class="helpImage" src="media/help.png"/>
                                </div>
                                <div id='pass1Message' class='message'</div>
                            </td>
                        </tr>
                        <tr>
                            <td>Confirm Password</td>
                            <td><input type="password" id="password2" class='regInput'></td>
                            <td>
                                <div id='pass2Message' class='message'</div>
                            </td>
                        </tr>
                        <tr>
                            <td></td><td><button onclick='register()' class='button1'>register</button></td><td></td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div id='registrationRight'>
                
            </div>
        </div>
    </div>
    <div id='workingDiv'>
        <div id='workingDivText'>working...
        </div>
    </div>

    <script>
        $("#workingDiv").hide();

        $("#helpPassword").mouseover(function(){
            $("#registrationRight").hide().fadeIn("slow").html("Password requirements:<br><ul><li>6 character minimum</li><li>that's it!</li></ul>");
        });
        $("#helpEmail").mouseover(function(){
            $("#registrationRight").hide().fadeIn("slow").html("You will use this email to sign-in or re-set your password if needed. A verification email will be sent with a confirmation link after registration.");
        });
        $("#helpName").mouseover(function(){
            $("#registrationRight").hide().fadeIn().html("Your user name will appear on all the Workflow Steps you complete.");
        });
        $("#helpCompany").mouseover(function(){
            $("#registrationRight").hide().fadeIn().html("This can be your business name, your real name, or a made up name.  It appears on reports and print outs.");
        });

    </script>
</body>
</html>

_FixedHTML;

?>