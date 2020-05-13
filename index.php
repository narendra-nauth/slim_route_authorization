<?php
    use \Psr\Http\Message\ServerRequestInterface as Request;
    use \Psr\Http\Message\ResponseInterface as Response;

    require_once('vendor/autoload.php');
    require_once('middleware/HttpBasicAuthentication.php');
	require_once('models/authentication.php');
	
	ini_set('memory_limit', '-1');

    $configuration = [
        'settings' => [
            'displayErrorDetails' 		=> true,
            'addContentLengthHeader' 	=> true,
        ],
    ];

    $con = new \Slim\Container($configuration);

    $container['notFoundHandler'] = function($container){
        return function($request, $response) use ($container){
            $message = array('message' => 'Invalid Method');

            return $container['response']
				->withStatus(404)
				->withHeader('Content-Type', 'text/html')
				->withJson($message);
        };
    };

    $container['notAllowedHandler'] = function($container){
        return function($request, $response, $methods) use ($container){
            $message = array('message' => 'Invalid Method');

            return $container['response']
				->withStatus(405)
				->withHeader('Allow', implode(', ', $methods))
				->withHeader('Content-Type', 'text/html')
				->withJson($message);
        };
    };

    $app 	= new \Slim\App($container);
	$users  = apiAuthentication::authenticeUser();
	
	//Slim 3 Middleware operates LIFO
	//Middleware for user level
	$app->add(function ($request, $response, $next) {
		$username 	= $request->getHeader('PHP_AUTH_USER');
		$userLevel 	= apiAuthentication::getUserLevel($username[0]);
		$request 	= $request->withAttribute('level', $userLevel['level']);

		$response = $next($request, $response);

		return $response;
	});
	
	//Middleware for basic auth
    $app->add(new \Slim\Middleware\HttpBasicAuthentication([
        "secure"    => false,
        "relaxed"   => ['localhost'],
        "users"     => $users,
        "error" => function ($request, $response, $arguments){
            $data = [];
            $data["status"] 	= "error";
            $data["message"] 	= $arguments["message"];
            return $response->write(json_encode($data, JSON_UNESCAPED_SLASHES));
        }
	]));

	$app->get('/admin', function($request, $response, $args){
		$level = $request->getAttribute('level');
		if($level != '1'){
			$status = 401;
			$result = array(
				'status'	=> 'error',
				'message'	=> 'Authorization failed'
			);
		}
		else{
			$status = 200;
			$result = array(
				'message'	=> 'Hi Admin',
			);
		}	

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($status)
			->withJson($result);
	});
	
	$app->get('/member', function($request, $response, $args){
		$level = $request->getAttribute('level');
		if($level != '2'){
			$status = 401;
			$result = array(
				'status'	=> 'error',
				'message'	=> 'Authorization failed'
			);
		}
		else{
			$status = 200;
			$result = array(
				'message'	=> 'Hi Member',
			);
		}	

		return $response
			->withHeader('Content-Type', 'application/json')
			->withStatus($status)
			->withJson($result);
	});
    
    $app->run();
?>
