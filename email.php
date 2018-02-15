<?php
include 'db.php';
require('mailer/class.phpmailer.php');
require 'codeguy-Slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;

// create new Slim instance
$app = new Slim();

$app->get("/f2", function () {
    echo "<h1>f2 azsys service</h1>";
});

$app->post('/contactus',
function () use ($app) {
   try{
   $request = $app->request();
   $queryDetails = json_decode($request->getBody());
   $uname = $queryDetails->name;
   $email = $queryDetails->email;
   $query = $queryDetails->query;

$message = "<div style='border:2px solid green'><b>Name :</b> ".$uname." <br><b>Email : </b>".$email."<br><b>Message : </b>".$query."</div>";
//echo $message;
   try {
   $mail = new PHPMailer();
      $mail->IsSMTP();
	  $mail->SMTPAuth = true;
	  $mail->SMTPSecure = 'ssl';
	  //$mail->SMTPDebug = 1;
	  $mail->Host = '';
	  $mail->Port       = 25;
	  $mail->Username = '';
	  $mail->Password = '';
	
	  $mail->SetFrom('', 'Team');
	  $mail->AddAddress($emailId, $userName);

	  $mail->Subject = 'Query raised by : '.$uname;
	  $mail->Body    = $message;
	  $mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

	  if(!$mail->Send())
	  {
	  //   echo 'Message could not be sent. <p>';
	  //   echo 'Mailer Error: ' . $mail->ErrorInfo;
	     exit;
	  }

	  //echo 'Message has been sent';

	  echo true;

   } catch(PDOException $e) {
      //error_log($e->getMessage(), 3, '/var/tmp/php.log');
      echo '{"error":{"text":'. $e->getMessage() .'}}';
   }
}catch(PDOException $e) {
      //error_log($e->getMessage(), 3, '/var/tmp/php.log');
      echo '{"error":{"text":'. $e->getMessage() .'}}';
   }
});


// run the Slim app
	  $app->run();

?>
