<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$app->get('/webapi/listarea', function (Request $request, Response $response) {	
		$sql = 'select * from area where status=1';
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
	
	
	
	$app->get('/webapi/listarea/{areaId}', function (Request $request, Response $response,array $parms) {
		$areaId = $parms['areaId'];
		$sql = "select * from area where id='".$areaId."'";
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


	$app->post('/webapi/addarea',function(Request $request,Response $response){		
		$args = json_decode($request->getBody(),true);
        $areaName      = $args["area_name"];		
        $areaId        = $args['area_id'];	
		$createdDate     = date("Y-m-d H:i:s");
		$createdBy 	     = $args["user_id"];
		$status          = $args["status"];

		$sql = "INSERT INTO area SET 	 `area_name`  = '".$areaName."',`area_id`  = '".$areaId."', `created_date`  = '".$createdDate."',`created_by`= '".$createdBy."', `status`= '".$status."'";
                                         # echo $sql;exit;
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

	$app->put('/webapi/modifyarea/{areaId}',function(Request $request,Response $response,array $parms){	
		$areaId = $parms['areaId'];
		$args = json_decode($request->getBody(),true);
		
		$areaName         = $args["area_name"];
		$modifiedDate     = date("Y-m-d H:i:s");
		$modifiedBy 	  = $args["user_id"];
		$status           = $args["status"];

		$sql = "UPDATE  area SET 	 `area_name`  = '".$areaName."', `modified_date`  = '".$modifiedDate."',`modified_by`= '".$modifiedBy."', `status`= '".$status."' WHERE id='".$areaId."'";
		try {
			$this->db->exec($sql);
			$data = array('message' => 'Area modified successfully');
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

	$app->delete('/webapi/deletearea/{areaId}',function(Request $request,Response $response,array $parms){	
		$areaId = $parms['areaId'];	
		$args = json_decode($request->getBody(),true);		
		$modifiedDate     = date("Y-m-d H:i:s");
		$modifiedBy 	 = $args["user_id"];
		$status          = 0;

		$sql = "UPDATE  area SET     `modified_date`	     = '".$modifiedDate."',
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