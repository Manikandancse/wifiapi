<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$app->get('/webapi/listplan', function (Request $request, Response $response) {	
		$sql = 'select * from plans where status=1';
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
	
	$app->get('/webapi/listplan/{planId}', function (Request $request, Response $response,array $parms) {
		$areaId = $parms['planId'];
		$sql = "select * from plans where id='".$areaId."'";
		try {
			
			$stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_OBJ);
            if($result){
                return	$this->response->withJson($result,200);
            }else{
                return	$this->response->withJson(array("message"=>"No result not found"),200);
            }
		}
		catch(PDOException $e) {
			$data = array('message' => $e->getMessage());
			$newResponse = $this->response->withJson($data,406);
			return $newResponse;
		}
	});


	$app->post('/webapi/addplan',function(Request $request,Response $response){		
		$args = json_decode($request->getBody(),true);
        $areaName      = $args["plan_id"];
		$planAmount      = $args["plan_amount"];
        $areaId        = $args['plan_id'];	
		$createdDate     = date("Y-m-d H:i:s");
		$createdBy 	     = $args["user_id"];
		$instllation_charge = $args["instllation_charge"];
		$router_charge 	    = $args["router_charge"];
		$status          = $args["status"];

		$sql = "INSERT INTO plans SET 	 `plan_name`  = '".$areaName."', `plan_amount`  = '".$planAmount."', 
				`instllation_charge`  = '".$instllation_charge."', `router_charge`  = '".$router_charge."', `plan_id`  = '".$areaId."', `created_date`  = '".$createdDate."',`created_by`= '".$createdBy."', `status`= '".$status."'";
                                         # echo $sql;exit;
		try {
			$this->db->exec($sql);
			$data = array('message' => 'Plan added successfully');
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

	$app->put('/webapi/modifyplan/{planId}',function(Request $request,Response $response,array $parms){	
		$areaId = $parms['planId'];
		$args = json_decode($request->getBody(),true);
		
		$areaName         = $args["plan_id"];
		$planAmount       = $args["plan_amount"];
		$modifiedDate     = date("Y-m-d H:i:s");
		$modifiedBy 	  = $args["user_id"];
		$instllation_charge = $args["instllation_charge"];
		$router_charge 	    = $args["router_charge"];
		$status           = $args["status"];

		$sql = "UPDATE  plans SET `plan_id`  = '".$areaName."', `plan_amount`  = '".$planAmount."', 
			`instllation_charge`  = '".$instllation_charge."', `router_charge`  = '".$router_charge."',`modified_date`  = '".$modifiedDate."',`modified_by`= '".$modifiedBy."', `status`= '".$status."' WHERE id='".$areaId."'";
		try {
			$this->db->exec($sql);
			$data = array('message' => 'Plan modified successfully');
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

	$app->delete('/webapi/deleteplan/{planId}',function(Request $request,Response $response,array $parms){	
		$areaId = $parms['planId'];	
		$args = json_decode($request->getBody(),true);		
		$modifiedDate     = date("Y-m-d H:i:s");
		$modifiedBy 	 = $args["user_id"];
		$status          = 0;

		$sql = "UPDATE  plans SET   `modified_date`	     = '".$modifiedDate."',
									  `modified_by` 	     = '".$modifiedBy."',
									  `status`  			 = '".$status."' WHERE 
									  `id`          = '".$areaId."' ";
		try {
			$this->db->exec($sql);
			$data = array('message' => 'Plan deleted successfully');
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