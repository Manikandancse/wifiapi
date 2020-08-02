<?php
	use \Psr\Http\Message\ServerRequestInterface as Request;
	use \Psr\Http\Message\ResponseInterface as Response;

	$app->get('/home/{name}', function (Request $request, Response $response, array $args) {
		$name = $args['name'];
		$sql = 'select * from customers';
		try {
			$stmt = $this->db->query($sql);
			$todos = $stmt->fetchAll(PDO::FETCH_OBJ);
			$this->response->withJson($todos);
		}
		catch(PDOException $e) {
			echo 'error'.$e->getMessage();
		}
		//$response->getBody()->write("Hello, $name");
		//return $response;
		$data = array('name' => 'Bob', 'age' => 40);
		$newResponse = $this->response->withJson($data);
		//$newResponse->getBody()->write("Hello, $name");
		return $newResponse;
	});

?>