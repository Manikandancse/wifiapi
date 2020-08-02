<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$app->get('/webapi/listuser', function (Request $request, Response $response) {	
		$sql = 'select * from customers';
		try {
			$stmt = $this->db->query($sql);
			$result = $stmt->fetchAll(PDO::FETCH_OBJ);
		return	$this->response->withJson($result,200);
		}
		catch(PDOException $e) {
			$data = array('message' => $e->getMessage());
			$newResponse = $this->response->withJson($data,406);
			return $newResponse;
		}
		
		
	});


	$app->post('/webapi/adduser',function(Request $request,Response $response){		
		$args = json_decode($request->getBody(),true);
		$customerId      = $args["customerId"];
		$customerName    = $args["customerName"];
		$customerEmail   = $args["customerEmail"];
		$customerMobile  = $args["customerMobile"];
		$customerAddress = $args["customerAddress"];
		$customerPinCode = $args["customerPinCode"];
		$customerRoleId  = $args["customerRoleId"];
		$paymentType 	 = $args["paymentType"];
		$createdDate     = date("Y-m-d H:i:s");
		$createdBy 	     = $args["userId"];
		$status          = 1;

		$sql = "INSERT INTO customers SET 	`customer_id`          = '".$customerId."',
										  `customer_name`		 = '".$customerName."',
										  `customer_email`	     = '".$customerEmail."',
										  `customer_mobileno`    = '".$customerMobile."',
										  `customer_address`	 = '".$customerAddress."',
										  `customer_pincode` 	 = '".$customerPinCode."',
										  `customer_roleid`      = '".$customerRoleId."',
										  `payment_received_type`= '".$paymentType."',
										  `created_date`	     = '".$createdDate."',
										  `created_by` 	    	 = '".$createdBy."',
										  `status`  			 = '".$status."'";
		try {
			$this->db->exec($sql);
			$data = array('message' => 'Customer added successfully');
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

	$app->put('/webapi/modifyuser/{customerId}',function(Request $request,Response $response,array $parms){	
		$customerId = $parms['customerId'];	
		$args = json_decode($request->getBody(),true);
		
		$customerName    = $args["customerName"];
		$customerEmail   = $args["customerEmail"];
		$customerMobile  = $args["customerMobile"];
		$customerAddress = $args["customerAddress"];
		$customerPinCode = $args["customerPinCode"];
		$customerRoleId  = $args["customerRoleId"];
		$paymentType 	 = $args["paymentType"];
		$modifiedDate     = date("Y-m-d H:i:s");
		$modifiedBy 	 = $args["userId"];
		$status          = 1;

		$sql = "UPDATE  customers SET  `customer_name`		 = '".$customerName."',
										  `customer_email`	     = '".$customerEmail."',
										  `customer_mobileno`    = '".$customerMobile."',
										  `customer_address`	 = '".$customerAddress."',
										  `customer_pincode` 	 = '".$customerPinCode."',
										  `customer_roleid`      = '".$customerRoleId."',
										  `payment_received_type`= '".$paymentType."',
										  `modified_date`	     = '".$modifiedDate."',
										  `modified_by` 	     = '".$modifiedBy."',
										  `status`  			 = '".$status."' WHERE 
										  `customer_id`          = '".$customerId."' ";
		try {
			$this->db->exec($sql);
			$data = array('message' => 'Customer modified successfully');
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

	$app->delete('/webapi/deleteuser/{customerId}',function(Request $request,Response $response,array $parms){	
		$customerId = $parms['customerId'];	
		$args = json_decode($request->getBody(),true);		
		$modifiedDate     = date("Y-m-d H:i:s");
		$modifiedBy 	 = $args["userId"];
		$status          = 0;

		$sql = "UPDATE  customers SET     `modified_date`	     = '".$modifiedDate."',
										  `modified_by` 	     = '".$modifiedBy."',
										  `status`  			 = '".$status."' WHERE 
										  `customer_id`          = '".$customerId."' ";
		try {
			$this->db->exec($sql);
			$data = array('message' => 'Customer deleted successfully');
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