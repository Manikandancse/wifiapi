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

        $sql = "SELECT a.`customer_id`, a.`customer_name`,a.`customer_email`,a.`customer_mobileno`,a.`customer_roleid`,a.`status`,b.`role_name` FROM customers
a LEFT JOIN roles b ON a.`customer_roleid` = b.`id`  WHERE (a.`customer_id`= '".$customerId."' AND a.`password` = '".$password."' ) OR (a.`customer_mobileno`= '".$mobileNo."'  AND a.`password` = '".$password."' )";      		
		try {
			$stmt = $this->db->query($sql);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);           
            if($result){           
            if($result['status'] == '1'){
                unset($result['status']);
                $role = array("roleId"=>$result["customer_roleid"],"roleName"=>$result['role_name']);
                $userDetail = array("customerId"=>$result["customer_id"],
                                    "customerName"=>$result["customer_name"],
                                    "customerMobile"=>$result["customer_mobileno"],
                                    "customerEmail"=>$result["customer_email"]);
                $result = array("message"=>"Login successfully",
                                "errerType"=>"success",
                                "role"=>$role,
                                "user"=>$userDetail);               
            }else  if($result['status'] == '0'){               
                $result = array("message"=>"Your account is disabled , please contact admin","errerType"=>"error") ;               
            }
            return	$this->response->withJson($result,200);
          }else{
            $result = array("message" => "Invalid userid or password","errerType"=>"error") ;   
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