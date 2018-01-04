<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';

//variable definitions
$pass = $email = $fail = "";
$code = '0'; //error in post

$date = date("Y-m-d");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
    if (isset($_POST['new_email'])){
        $email = fix_string($_POST["new_email"]);
        $pass = fix_string($_POST["new_pass1"]);
        $pass2 = fix_string($_POST["new_pass2"]);
        $token = fix_string($_POST["new_token"]);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        //validate inputs
        $fail .= valPass($pass);
        $fail .= valPassComp($pass,$pass2);

        //check email and token
        $sql = "SELECT USR_id, USR_hash
                FROM USR
                WHERE USR_email = '$email'";

        $result=mysqli_query($conn,$sql);

        //String validation for input

        if($result->num_rows == 0){
            
            $code = '1'; //1 email not found

        } else {

            $row = $result->fetch_assoc();

            $existingVer = $row['USR_hash'];

            if ($existingVer == $token) {

                //verified, update
                $hash = password_hash($pass, PASSWORD_DEFAULT);
                $hash2 = md5(rand(0,1000));

                $sql =  "UPDATE USR
                            SET USR_key = '$hash', USR_hash = '$hash2'
                            WHERE USR_email = '$email'";

                $result=mysqli_query($conn,$sql);
                
                $code = '3'; //all good, updated

                $message = "<html>
                                <body>
                                <img src='cid:logo' width='300'>
                                <br><br>
                                <h3>Password Change Confirmation.</h3>
                                <br>
                                <h4>Your Workflow Labs password was updated $date.</h4> 
                                <br>
                                <h4>If you did not authorize this change, contact us immediately.</h4> 
                                </body>
                            </html>";

                $emailCheck = sendEmail($email, $message);

            } else {

                $code = '2'; //verification token doesn't match

            }
        }
    }
}

echo $code;

function valPassComp($field1,$field2)
{
	if ($field1 != $field2) return "Passwords don't match.<br>";
	else return "";
}

function valPass($field)
{
	if ($field == "") return "No Password was entered<br>";
	else if (strlen($field) < 4)
		return "Passwords must be at least 6 characters<br>";
	else return "";
}

function fix_string($string)
{
	return stripslashes($string);
}

function sendEmail($email, $message){
    
        require("../../lib/PHPMailer/PHPMailerAutoload.php");
        
        $mail = new PHPMailer;
        $mail->IsHTML(true);
        $mail->isSMTP();
        $mail->AddEmbeddedImage('../../media/logo6.png', 'logo');
        //Enable SMTP debugging
        // 0 = off (for production use)
        // 1 = client messages
        // 2 = client and server messages
        $mail->SMTPDebug = 0;
        //Ask for HTML-friendly debug output
        //$mail->Debugoutput = 'html';
        //Set the hostname of the mail server
        $mail->Host = 'secure211.inmotionhosting.com';
        // use
        //$mail->Host = gethostbyname('smtp.gmail.com');
        // if your network does not support SMTP over IPv6
        //Set the SMTP port number - 587 for authenticated TLS, a.k.a. RFC4409 SMTP submission
        $mail->Port = 465;
        //Set the encryption system to use - ssl (deprecated) or tls
        $mail->SMTPSecure = 'ssl';
        //Whether to use SMTP authentication
        $mail->SMTPAuth = true;
        //Username to use for SMTP authentication - use full email address for gmail
        $mail->Username = "info@lab627.com";
        //Password to use for SMTP authentication
        $mail->Password = "SQFdpLiA5HlG";
        //Set who the message is to be sent from
        $mail->setFrom('info@lab627.com', 'Workflow Lab');
        //Set an alternative reply-to address
        $mail->addReplyTo('info@lab627.com', 'Workflow Lab');
        //Set who the message is to be sent to
        //$mail->addAddress('3106256742@txt.att.net');//ian
        $mail->addAddress($email);
    
        //$mail->addAddress('3105289568@txt.att.net'); //kevin
        //Set the subject line
        $mail->Subject = 'Workflow Lab Password Change Notification';
        //Read an HTML message body from an external file, convert referenced images to embedded,
        //convert HTML into a basic plain-text alternative body
        //$mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
        $mail->msgHTML($message);
        //Replace the plain text body with one created manually
        $mail->AltBody = $message;
        //Attach an image file
        //$mail->addAttachment('images/phpmailer_mini.png');
        //send the message, check for errors
        if (!$mail->send()) {
            return 0;
        } else {
           return 1;
        }
    
    }

?>