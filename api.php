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

$app->get('/routes/:key',
function ($key) use ($app) {
   try{
	if($key == 'cOjxzK4vGc7310'){
    $request = $app->request();
   
			   try {

				  $result = null;

					$sql = "SELECT * FROM routes";

					$db = getDB();
					$stmt = $db->prepare($sql);
					$stmt->execute();
					 $allRoutes = $stmt->fetchAll();
					 $db = null;
					 $result = null;
					 $result = '{"resultObj":{"routes":'.json_encode($allRoutes).',"status":"SUCCESS"}}';

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

$app->get('/routes/:key/:id',
function ($key,$id) use ($app) {
   try{
	if($key == 'cOjxzK4vGc7310'){
	   try {

				  $result = null;

					$sql = "SELECT rs.id,r.route_id,r.bus_no,rs.trip_id,r.route_name,rs.stop_number,rs.leg_minutes,rs.time_here,ss.stop_name FROM routes r, route_detail rs,stops ss WHERE rs.route_id = $id AND rs.stop_id = ss.stop_id AND r.route_id = rs.route_id AND rs.leg_minutes = 0";

					$db = getDB();
					$stmt = $db->prepare($sql);
					$stmt->execute();
					 $allSubRoutes = $stmt->fetchAll();
					 $db = null;
					 $result = null;
					 $result = '{"resultObj":{"subroutes":'.json_encode($allSubRoutes).',"status":"SUCCESS"}}';

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

$app->get('/routes/:key/:id/:trip_id',
function ($key,$id,$trip_id) use ($app) {
   try{
	if($key == 'cOjxzK4vGc7310'){
	   try {

				  $result = null;
					/*if($timeNext == "last"){
						$timeNext = "23:59:00";
					}*/
					$sql = "SELECT rs.stop_number,rs.leg_minutes,rs.time_here,ss.stop_name FROM routes r, route_detail rs,stops ss WHERE rs.route_id = $id AND rs.stop_id = ss.stop_id AND r.route_id = rs.route_id AND rs.trip_id = '$trip_id'";

					$db = getDB();
					$stmt = $db->prepare($sql);
					$stmt->execute();
					 $allSubRoutes = $stmt->fetchAll();
					 $db = null;
					 $result = null;
					 $result = '{"resultObj":{"subroutes":'.json_encode($allSubRoutes).',"status":"SUCCESS"}}';

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

$app->get('/locations/:key/:q',
function ($key,$q) use ($app) {
   try{
	if($key == 'cOjxzK4vGc7310'){
   $request = $app->request();
   
			   try {

				  $result = null;

					$sql = "SELECT * FROM stops WHERE LOWER(stop_name) LIKE LOWER('%$q%')";
					
					$db = getDB();
					$stmt = $db->prepare($sql);
					$stmt->execute();
					 $locs = $stmt->fetchAll();
					 $db = null;
					 $result = null;
					// $result = '{"resultObj":{"locations":'.json_encode($locs).',"status":"SUCCESS"}}';
					$result = json_encode($locs);

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

$app->get('/locations/:key/:from/:to/:timeVal',
function ($key,$from,$to,$timeVal) use ($app) {
	
   try{
	if($key == 'cOjxzK4vGc7310'){
   $request = $app->request();
   
			   try {

				  $result = null;

					$sql = "SELECT DISTINCT r.route_id, r.route_name,r.bus_no,rs.stop_number,ss.stop_name AS FromLoc,
								se.stop_name AS ToLoc,rs.leg_minutes,rs.time_here
								FROM routes r
								JOIN route_detail rs ON (rs.route_id = r.route_id)
								JOIN stops ss ON (ss.stop_id = rs.stop_id)
								JOIN route_detail re ON (re.route_id = r.route_id)
								JOIN stops se ON (se.stop_id = re.stop_id)
								WHERE ss.stop_id = $from AND se.stop_id = $to AND rs.id < re.id AND rs.stop_number < re.stop_number AND rs.time_here > '$timeVal' order by rs.time_here" ;
					
					$db = getDB();
					$stmt = $db->prepare($sql);
					$stmt->execute();
					 $locs = $stmt->fetchAll();
					 $db = null;
					 $result = null;
					 $result = '{"resultObj":{"locations":'.json_encode($locs).',"status":"SUCCESS"}}';

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

// run the Slim app
$app->run();
?>