<?php
//Import PHPMailer classes into the global namespace
//These must be at the top of your script, not inside a function
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

function nameValidator($val){
    if(empty($val)){
        return true;
    }
    if(!preg_match("/^[a-zA-Z\s]*$/", $val)){
        return true;
    }
    return false;
}
function emailValidator($val){
    if(empty($val)){
        return true;
    }
    if (!filter_var($val, FILTER_VALIDATE_EMAIL)) {
        return true;
    }
    return false;
}
function phoneValidator($val){
    if(empty($val)){
        return true;
    }
    if(!preg_match("/^[0-9]*$/", $val)){
        return true;
    }
    return false;
}
function subject_messageValidator($val){
    if(empty($val)){
        return true;
    }
    if(!preg_match("/^[a-z 0-9~%.:_\@\-\/\(\)\\\#\;\[\]\{\}\$\!\&\<\>\'\r\n+=,]+$/i", $val)){
        return true;
    }
    return false;
}
function captchaValidator($val){
    if(empty($val)){
        return true;
    }
    // Put secret key here, which we get
    // from google console
    $secret_key = '6LejGTMjAAAAAKBhwRTSGDgAWh__xJmK_4lxxLsZ';
  
    // Hitting request to the URL, Google will
    // respond with success or error scenario
    $url = 'https://www.google.com/recaptcha/api/siteverify?secret='
          . $secret_key . '&response=' . $val;
    // Making request to verify captcha
    $response = file_get_contents($url);
  
    // Response return by google is in
    // JSON format, so we have to parse
    // that json
    $response = json_decode($response);
  
    // Checking, if response is true or not
    if ($response->success != true) {
        return true;
    }
    return false;
}

$name      =  $_POST['name'];
$email     =  $_POST['email'];
$phone     =  $_POST['phone'];
// $recaptcha = $_POST['g-recaptcha-response'];

if(nameValidator($name)){
    http_response_code(400); 
    echo json_encode(array("form_error"=>array("name"=>"Name field is invalid")));
    exit;
}
if(emailValidator($email)){
    http_response_code(400); 
    echo json_encode(array("form_error"=>array("email"=>"Email field is invalid")));
    exit;
}
if(phoneValidator($phone)){
    http_response_code(400); 
    echo json_encode(array("form_error"=>array("phone"=>"Phone field is invalid")));
    exit;
}
// if(captchaValidator($recaptcha)){
//     http_response_code(400); 
//     echo json_encode(array("form_error"=>array("recaptcha"=>"Recaptcha field is required")));
//     exit;
// }

    $body='<h2>Ace Global</h2>

    <table style="width:100%">
    <tr>
        <td style="height:40px">Fullname</td>
        <td style="height:40px">'.$name.'</td>
    </tr>
    <tr>
        <td style="height:40px">Email</td>
        <td style="height:40px">'.$email.'</td>
    </tr>
    <tr>
        <td style="height:40px">Mobile</td>
        <td style="height:40px">'.$phone.'</td>
    </tr>                     
    </table>';


//Load Composer's autoloader
require 'vendor/autoload.php';
//Create an instance; passing `true` enables exceptions
$mail = new PHPMailer(true);
try {
    //Server settings
   // $mail->SMTPDebug = 3;//SMTP::DEBUG_SERVER;                      //Enable verbose debug output
    $mail->isSMTP();                                            //Send using SMTP
    $mail->Host       = 'mail.ashwasuryarealities.com';                     //Set the SMTP server to send through
    $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
    $mail->Username   = 'info@ashwasuryarealities.com';                     //SMTP username
    $mail->Password   = 'C}CL?~^HQ*mM';                               //SMTP password
    $mail->SMTPSecure = 'tls'; //PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
    $mail->Port       = 587;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
    //Recipients
    $mail->setFrom('info@ashwasuryarealities.com', 'Ace Global');
    $mail->addAddress('subham.5ine@gmail.com', 'Ace Global');     //Add a recipient
    //$mail->addAddress('ellen@example.com');               //Name is optional
    //$mail->addReplyTo('info@example.com', 'Information');
    //$mail->addCC('cc@example.com');
    //$mail->addBCC('bcc@example.com');
    //Attachments
    //$mail->addAttachment('/var/tmp/file.tar.gz');         //Add attachments
    //$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    //Optional name
    //Content
    $mail->isHTML(true);                                  //Set email format to HTML
    $mail->Subject = 'Here is the subject';
    $mail->Body    =  $body;
    $mail->send();
    //echo 'Message has been sent';
    http_response_code(200); 
    echo json_encode(array("message"=>"Mail sent successfully"));
    exit;
    
   
} catch (Exception $e) {
    http_response_code(400); 
    echo json_encode(array("error"=>"Message could not be sent. Mailer Error: {$mail->ErrorInfo}"));
    exit;
}
?>