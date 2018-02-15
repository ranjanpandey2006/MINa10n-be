<?php
require 'codeguy-Slim/Slim/Slim.php';
\Slim\Slim::registerAutoloader();
use \Slim\Slim AS Slim;
// create new Slim instance
$app = new Slim();

// Services for mobile_chargers
$app->get("/getAllMobileChagers", function () {
   $allMobiles = file_get_contents('http://api.pricecheckindia.com/feed/product/mobile_chargers.json?user=laaptula&key=NADSGUVCVBRPRETN');
echo $allMobiles;
});
$app->get('/getAllMobileChagersForSelBrand/:brand', function ($brand) {
   $mobilesForSelBrand=
file_get_contents("http://api.pricecheckindia.com/feed/product/mobile_chargers/".$brand.".json?user=laaptula&key=NADSGUVCVBRPRETN");
   echo $mobilesForSelBrand;
});
$app->get('/getAllMobileChagersForSelModel/:model', function ($model) {
   $mobilesForSelModel=
file_get_contents("http://api.pricecheckindia.com/feed/product/mobile_chargers/".$model.".json?user=laaptula&key=NADSGUVCVBRPRETN");
   echo $mobilesForSelModel;
});
// End of mobile_chargers services

$app->get("/f2", function () {
    echo "<h1>f2 service</h1>";
});
$app->get('/f3/:name', function ($name) {
    echo "Hello, $name";
});
$app->post('/register',
function () use ($app) {
   try{
   $request = $app->request();
   $userRegister = json_decode($request->getBody());
   $user_email = $userRegister->user_email;
   $user_password = $userRegister->user_password;
   $user_phone = $userRegister->user_phone ;

   $sql = "INSERT INTO user (`user_email`,`user_password`,`user_phone`)
   VALUES ('$user_email','$user_password','$user_phone')";
   try {
      $db = getDB();
      $stmt = $db->prepare($sql);
      $res = $stmt->execute();
      $db = null;
      $result = null;
      if ($res !=0 ) {
         $result = "SUCCESS";
      }else{
         $result = "FAILURE";
      }
      echo $result;
   } catch(PDOException $e) {
      //error_log($e->getMessage(), 3, '/var/tmp/php.log');
      echo '{"error":{"text":'. $e->getMessage() .'}}';
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
   $userRegister = json_decode($request->getBody());
   $user_email = $userRegister->user_email;
   $user_password = $userRegister->user_password;

   $sql = "SELECT * FROM user WHERE user_email='$user_email' && user_password='$user_password'";
   try {
      $db = getDB();
      $stmt = $db->prepare($sql);
      $stmt->execute();
      $stmt->bindParam("user_email", $user_email);
      $stmt->bindParam("user_password", $user_password);
     $stmt->execute();
     $userFetch = $stmt->fetchObject();
     $db = null;
     $result = null;
     if($userFetch != null){
         $result =  '{"resultObj":{"userObj":'.json_encode($userFetch).',"status":"SUCCESS"}}';
     }else{
         $result =  '{"resultObj":{"status":"FAILURE"}}';
     }
     echo $result;
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