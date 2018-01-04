<?php

//initiate the session (must be the first statement in the document)
session_start();

//linked files
require_once 'dbConnect.php';

//variable definitions
$pass = $email = $fail = "";

$date = date("Y-m-d");

if ( $_SERVER['REQUEST_METHOD'] == 'POST' ) {
    
    if (isset($_POST['new_email'])){
        $email = fix_string($_POST["new_email"]);
        $pass = fix_string($_POST["new_pass1"]);
        $pass2 = fix_string($_POST["new_pass2"]);
        $uname = fix_string($_POST["new_user"]);
        $cust = fix_string($_POST["new_cust"]);

        // Check connection
        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }
        
        $fail .= valCust($cust);
        $fail .= valUser($uname);
        $fail  .= valEM($email, $conn);
        $fail .= valPass($pass);
        $fail .= valPassComp($pass,$pass2);
    
        if ($fail == ""){

            $hash = password_hash($pass, PASSWORD_DEFAULT);

            $hash2 = md5(rand(0,1000));

            $stmt = $conn->prepare("INSERT INTO CUS (CUS_name) VALUES (?)");
            $stmt->bind_param("s", $cust);

            $stmt->execute();

            $custID = $stmt->insert_id;

            $defaultVer = 0;

            $stmt = $conn->prepare("INSERT INTO USR (USR_email, USR_key, USR_CUS_id, USR_name, USR_ver, USR_joinDate, USR_accessDate, USR_hash) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssisisss", $email, $hash, $custID, $uname, $defaultVer, $date, $date, $hash2);

            $stmt->execute();

            $userID = $stmt->insert_id;

            $_SESSION['userID'] = $userID;
            $_SESSION['custID'] = $custID;

            $link = "<a href='http://localhost/workflowlab_beta/php/fp/verification.php?email=$email&t=$hash2'>HERE</a>";

            $message = "<html>
                            <body>
                            <img src='cid:logo' width='300'>
                            <br><br>
                            <h4>Thank you for registering with Workflow Labs.</h4>
                            <br>
                            <h3>Click $link to finalize your registration.</h3>
                            <br>
                            <h4>Link not working?  Copy and paste this into your browser:<br><h4>
                            <h4>http://localhost/workflowlab_beta/php/fp/verification.php?email=$email&t=$hash2</h4> 
                            </body>
                        </html>";
            
            $result = sendEmail($email, $message);
            
            echo $result;
        } 
        
    }

}

echo $fail;

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

function valEM($email, $conn)
{

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return "Please check the email (it looks weird to us).<br>";

      } else {

        $sql = "SELECT USR_id
        FROM USR
        WHERE USR_email = '$email'";

        $result=mysqli_query($conn,$sql);

        //String validation for input

        if($result->num_rows == 0){
            return "";
        } else {
            return "Email is already in use.<br>";
        }
    }

}

function valUser($field)
{
	if ($field == "") {
        return "A user name is required.<br>";
    } else if (strlen($field) < 1){
		return "Please complete all fields.<br>";
	}
}

function valCust($field)
{
	if ($field == "") {
        return "A company name is required.<br>";
    } else if (strlen($field) < 1){
		return "A company name is required.<br>";
	}
}

function fix_string($string)
{
	return stripslashes($string);
}

//php functions
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
        $mail->Subject = 'Workflow Lab Registration';
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
            echo "Oh no.  Something happened.<br>
                Mailer Error: " . $mail->ErrorInfo;
        } else {
            echo "Account created.<br>
            A verification email has been sent to $email to make sure we've got it right.<br>
            Click on the included link to start using Workflow Labs.";
        }
    
    }
?>