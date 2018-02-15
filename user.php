<?php
include 'db.php';
require 'codeguy-Slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
// create new Slim instance
$app = new Slim();

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Max-Age: 1000");
header("Access-Control-Allow-Headers: X-Requested-With, Content-Type, Origin, Cache-Control, Pragma, Authorization, Accept, Accept-Encoding");
header("Access-Control-Allow-Methods: PUT, POST, GET, OPTIONS, DELETE");

$app->get("/v1", function () {
    echo "<h1>Welcome to MIN Automation</h1>";
});
$app->get('/v1/:name', function ($name) {
    echo "Hello, $name";
});

$app->get('/v1/:key/services',
function ($key) use ($app) {
   try{
		if($key == 'cOjxzK4vGc7310'){
			$request = $app->request();
			try {
				$result = null;
				$sql = "SELECT * FROM service";
				$db = getDB();
				$stmt = $db->prepare($sql);
				$stmt->execute();
				$allRoutes = $stmt->fetchAll();
				$db = null;
				$result = null;
				$result = '{"resultObj":{"services":'.json_encode($allRoutes).',"status":"SUCCESS"}}';

				echo $result;
				
			} catch(PDOException $e) {
				//error_log($e->getMessage(), 3, '/var/tmp/php.log');
				echo '{"error":{"text":'. $e->getMessage() .'}}';
			}
		}else{
			echo '{"error":{"text":"Invalid API key"}}';
		}
	}catch(PDOException $e) {
      //error_log($e->getMessage(), 3, '/var/tmp/php.log');
      echo '{"error":{"text":'. $e->getMessage() .'}}';
   }
});

$app->post('/login',
function () use ($app) {
   try{
		$request = $app->request();
		$queryDetails = json_decode($request->getBody());
		$uname = $queryDetails->username;
		$passwd = $queryDetails->passwd;
		$email = $queryDetails->email;

	try {
			if(($key == 'cOjxzK4vGc7310')){
				$request = $app->request();
				try {

					$result = null;

						$sql = "SELECT * FROM user WHERE email=$email AND passwd=$passwd";

						$db = getDB();
						$stmt = $db->prepare($sql);
						$stmt->execute();
						$allRoutes = $stmt->fetchObject();
						$db = null;
						$result = null;
						if(null == $allRoutes){
							$result = '{"resultObj":{"status":"FAIL"}}';
						}else{
							$result = '{"resultObj":{"status":"SUCCESS"}}';
						}

					echo $result;
					
				} catch(PDOException $e) {
					//error_log($e->getMessage(), 3, '/var/tmp/php.log');
					echo '{"error":{"text":'. $e->getMessage() .'}}';
				}
			}else{
				echo '{"error":{"text":"Invalid API key"}}';
			}

		} catch(PDOException $e) {
		//error_log($e->getMessage(), 3, '/var/tmp/php.log');
		echo '{"error":{"text":'. $e->getMessage() .'}}';
		}
	}
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