<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$app->get('/webapi/listgst', function (Request $request, Response $response) {	
		$sql = 'select * from gst where status=1';
		try {
			$stmt = $this->db->query($sql);
            $result = $stmt->fetchAll(PDO::FETCH_OBJ);
            if($result){
                return	$this->response->withJson(array('results'=>$result),200);
            }else{
                return	$this->response->withJson(array('results'=>[],"message"=>"Area not found"),200);
            }
		
		}
		catch(PDOException $e) {
			$data = array('message' => $e->getMessage());
			$newResponse = $this->response->withJson($data,406);
			return $newResponse;
		}
	});
	
	$app->get('/webapi/listgst/{planId}', function (Request $request, Response $response,array $parms) {
		$areaId = $parms['planId'];
		$sql = "select * from gst where id='".$areaId."'";
		try {
			
			$stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if($result){
                return	$this->response->withJson($result,200);
            }else{
                return	$this->response->withJson(array("message"=>"Area not found"),200);
            }
		}
		catch(PDOException $e) {
			$data = array('message' => $e->getMessage());
			$newResponse = $this->response->withJson($data,406);
			return $newResponse;
		}
	});


	$app->post('/webapi/addgst',function(Request $request,Response $response){		
		$args = json_decode($request->getBody(),true);
        $areaName      	= $args["gst_id"];
		$planAmount     = $args["gst_percent"];
		$gstFrom     	= $args["gst_from"];
		$gstTo     		= $args["gst_to"];
		$createdDate    = date("Y-m-d H:i:s");
		$createdBy 	    = $args["user_id"];
		$status         = $args["status"];

		$sql = "INSERT INTO gst SET `gst_id`  = '".$areaName."', `gst_percent`  = '".$planAmount."', `gst_fromdate`  = '".$gstFrom."', `gst_todate`  = '".$gstTo."', `created_date`  = '".$createdDate."',`created_by`= '".$createdBy."', `status`= '".$status."'";
		try {
			$this->db->exec($sql);
			$data = array('message' => 'Area added successfully');
			$newResponse = $this->response->withJson($data,200);
			return $newResponse;
		}
		catch(PDOException $e) {
			#echo 'error'.$e->getMessage();
			$data = array('message' => $e->getMessage());
			$newResponse = $this->response->withJson($data,406);
			return $newResponse;
		}										  

	});

	$app->put('/webapi/modifygst/{planId}',function(Request $request,Response $response,array $parms){	
		$areaId = $parms['planId'];
		$args = json_decode($request->getBody(),true);
		
		$areaName      	= $args["gst_id"];
		$planAmount     = $args["gst_percent"];
		$gstFrom     	= date("Y-m-d H:i:s");
		$gstTo     		= date("Y-m-d H:i:s");
		$modifiedDate     = date("Y-m-d H:i:s");
		$modifiedBy 	  = $args["user_id"];
		$status           = $args["status"];


		$sql = "UPDATE  gst SET `gst_id`  = '".$areaName."', `gst_percent`  = '".$planAmount."', `gst_fromdate`  = '".$gstFrom."', `gst_todate`  = '".$gstTo."',  `modified_date`  = '".$modifiedDate."',`modified_by`= '".$modifiedBy."', `status`= '".$status."' WHERE id='".$areaId."'";
		try {
			$this->db->exec($sql);
			$data = array('message' => 'GST modified successfully');
			$newResponse = $this->response->withJson($data,200);
			return $newResponse;
		}
		catch(PDOException $e) {
			#echo 'error'.$e->getMessage();
			$data = array('message' => $e->getMessage());
			$newResponse = $this->response->withJson($data,406);
			return $newResponse;
		}										  

	});

	$app->delete('/webapi/deletegst/{planId}',function(Request $request,Response $response,array $parms){	
		$areaId = $parms['planId'];	
		$args = json_decode($request->getBody(),true);		
		$modifiedDate     = date("Y-m-d H:i:s");
		$modifiedBy 	 = $args["user_id"];
		$status          = 0;

		$sql = "UPDATE  gst SET  `modified_date`	     = '".$modifiedDate."',
									  `modified_by` 	     = '".$modifiedBy."',
									  `status`  			 = '".$status."' WHERE 
									  `id`          = '".$areaId."' ";
		try {
			$this->db->exec($sql);
			$data = array('message' => 'Area deleted successfully');
			$newResponse = $this->response->withJson($data,200);
			return $newResponse;
		}
		catch(PDOException $e) {
			#echo 'error'.$e->getMessage();
			$data = array('message' => $e->getMessage());
			$newResponse = $this->response->withJson($data,406);
			return $newResponse;
		}										  

	});

	?>