<?php
session_start();
if (isset($_POST['captcha']) && ($_POST['captcha'] != "")) {
   if (strcasecmp($_SESSION['captcha'], $_POST['captcha']) == 0) {
      if (!isset($_POST["betreff"])) {
         $to_email = "support@mikropi.de";
         $subject = "Anfrage von " . $_POST['name'];
         $body = $_POST['message'];
         $headers   = array();
         $headers[] = "MIME-Version: 1.0";
         $headers[] = "Content-type: text/plain; charset=utf-8";
         $headers[] = "From: {$_POST['email']}";
         $headers[] = "Reply-To: {$$_POST['email']}";
         $headers[] = "Subject: {$subject}";
         $headers[] = "X-Mailer: PHP/" . phpversion();
         if (mail($to_email, $subject, $body, implode("\r\n", $headers))) {
            header('Location: index.php');
         } else {
            echo ("Email sending failed...");
         }
      } else {
         $to_email = "admin@mikropi.de";
         $subject = "Bug Report von " . $_POST['name'] . " - " . $_POST['betreff'];
         $body = nl2br($_POST['message']);
         $header  = "MIME-Version: 1.0\r\n";
         $header .= "Content-type: text/html; charset=utf-8\r\n";
         $header .= "From: support@mikropi.de\r\n";
         $header .= "Reply-To: ".$_POST['email']."\r\n";
         $header .= "X-Mailer: PHP " . phpversion();

         if (mail($to_email, $subject, $body, $header)) {
            header('Location: report.php?sent');
         } else {
            header('Location: report.php?failed');
         }
      }
   } else {
      if (!isset($_POST["betreff"])) {
         header('Location: report.php?failedCaptcha');
      }
   }
   echo ("Captcha wrong, Please go back");
}else{
   echo ("Captcha wrong, Please go back");
}
