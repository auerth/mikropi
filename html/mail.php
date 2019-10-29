<?php

include("../etc/recaptcha.php");
$json = file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=' . $secretkey . '&response=' . $_POST['g-recaptcha-response']);
$data = json_decode($json, true);
if ($data["success"]) {
   if(!isset($_POST["betreff"])){
      $to_email = "support@mikropi.de";
      $subject = "Anfrage von " . $_POST['name'];
      $body = $_POST['message'];
      $headers   = array();
      $headers[] = "MIME-Version: 1.0";
      $headers[] = "Content-type: text/plain; charset=utf-8";
      $headers[] = "From: {$_POST['email']}";
      $headers[] = "Reply-To: {$$_POST['email']}";
      $headers[] = "Subject: {$subject}";
      $headers[] = "X-Mailer: PHP/".phpversion();
      if (mail($to_email, $subject, $body, implode("\r\n",$headers))) {
         header('Location: index.php');
      } else {
         echo ("Email sending failed...");
      }
   }else{
      $to_email = "admin@mikropi.de";
      $subject = "Anfrage von " . $_POST['name'] . " - " . $_POST['betreff'];
      $body = $_POST['message'];
      $headers   = array();
      $headers[] = "MIME-Version: 1.0";
      $headers[] = "Content-type: text/plain; charset=utf-8";
      $headers[] = "From: {$_POST['email']}";
      $headers[] = "Reply-To: {$$_POST['email']}";
      $headers[] = "Subject: {$subject}";
      $headers[] = "X-Mailer: PHP/".phpversion();

      if (mail($to_email, $subject, $body, implode("\r\n",$headers))) {
         header('Location: report.php?sent');
      } else {
         header('Location: report.php?failed');
      }
   }
}else{
   if(!isset($_POST["betreff"])){
      header('Location: report.php?failedCaptcha');
   }
}
echo ("Captcha wrong, Please go back");
