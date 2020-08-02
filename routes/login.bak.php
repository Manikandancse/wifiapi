<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$app->post('/webapi/login', function (Request $request, Response $response) {
        
        $args = json_decode($request->getBody(),true);
       # print_r($args);
        $customerId = $mobileNo = '';
        $password   = $args["password"];
        if(isset($args["customerMobile"])){
            $mobileNo = $args["customerMobile"];
        }
        if(isset($args["customerId"])){
           $customerId = $args["customerId"];
        }

        $sql = "SELECT `customer_id`, `customer_name`,`customer_email`,`customer_mobileno`,`customer_roleid`,`status` FROM customers
            WHERE (`customer_id`= '".$customerId."' AND `password` = '".$password."' ) OR (`customer_mobileno`= '".$mobileNo."'  AND `password` = '".$password."' )";      		
		try {
			$stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);           
            if($result){           
            if($result['status'] == '1'){
                unset($result['status']);
                $result = array("message"=>"Logged in successfully") + $result;               
            }else  if($result['status'] == '0'){               
                $result = array("message"=>"Your account is disabled , please contact admin") ;               
            }
            return	$this->response->withJson($result,200);
          }else{
            $result = array("message" => "Invalid userid or password") ;   
            return	$this->response->withJson($result,200);
          }     
		
		}
		catch(PDOException $e) {
			$data = array('message' => $e->getMessage());
			$newResponse = $this->response->withJson($data,406);
			return $newResponse;
		}
		
		
    });
    
?>