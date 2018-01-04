<?php

//linked files
require_once 'dbConnect.php';

$email = $link = $message = "";

if (isset($_POST['ver_email'])){

    $email = $_POST['ver_email'];

    //check emai
    $sql = "SELECT USR_id
    FROM USR
    WHERE USR_email = '$email'";

    $result=mysqli_query($conn,$sql);

    //String validation for input

    if($result->num_rows == 0){
        
        echo "We couldn't find a pernding account for $email.<br>
                Go <a href='../index.php#registration'>HERE</a> to sign up.";

    } else {
            
        $row = $result->fetch_assoc();
        
        $hash2 = md5(rand(0,1000));

        $sql =  "UPDATE USR
                SET USR_hash = '$hash2'
                WHERE USR_email = '$email'";

        $result=mysqli_query($conn,$sql);

        $link = "<a href='http://localhost/workflowlab_beta/php/fp/verification.php?email=$email&t=$hash2'>HERE</a>";
        
        $message = "<html>
                        <body>
                        <img src='cid:logo' width='300'>
                        <br><br>
                        <h4>Thank you for regirstering with Workflow Labs.</h4>
                        <br>
                        <h3>Click $link to finalize your registration.</h3>
                        <br>
                        <h4>Link not working?  Copy and paste this into your browser:<br><h4>
                        <h4>http://localhost/workflowlab_beta/php/fp/verification.php?email=$email&t=$hash2</h4> 
                        </body>
                    </html>";
        
        $result = sendEmail($email, $message);       

    }

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
            echo "There was a problem sending the verification email.<br>Please try again.<br>
            <input type='button' onclick='resendVerification()' class='button buttonBlue' value='Resend verification email.'><br>
            <a href='mailto:info@lab627.com'>Contact Us</a> if you still have problems.<br>
            ref: " . $mail->ErrorInfo;
        } else {
            echo "Verification email re-sent to $email.<br>
            Click on the included link to start using Workflow Labs.<br>
            <a href='mailto:info@lab627.com'>Contact Us</a> if you still have problems.";
        }
    
    }


?>