<?php

use Firebase\JWT\JWT;
use Ramsey\Uuid\Uuid;
use Slim\Http\Request;
use Slim\Http\Response;

// Routes
$app->add(function (Request $request, Response $resp, $next) use($app) {
    $response = $next($request, $resp);
    return $response
                    ->withHeader('Access-Control-Allow-Origin', '*')
                    ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
                    ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, PATCH, OPTIONS');
});

$app->get('/', function (Request $request, Response $response, array $args) {
    //$this->logger->info("No Authentication!");
    return $response->withJson(["status" => "ok"]);
});

$app->get('/api', function (Request $request, Response $response, array $args) {
    //$this->logger->info("Authenticated!");
    return $response->withJson(["status" => "ok"]);
});

$app->group('/login', function () use ($app) {
    $app->get("/token", function (Request $request, Response $response, array $args) {
        $settings = $this->get('settings');
        //$tokenId = Uuid::uuid1()->toString();
        $issuedAt = time();
        $notBefore = $issuedAt;
        $expire = $notBefore + 60 * 60;
        $serverName = $request->withUri($request->getUri(), true);
        $token = JWT::encode([
                    'iat' => $issuedAt, // Issued at: time when the token was generated
                    //'jti'  => $tokenId,          // Json Token Id: an unique identifier for the token
                    'iss' => $serverName, // Issuer
                    'nbf' => $notBefore, // Not before
                    'exp' => $expire,
                    'dta' => [ // payload
                        'id' => 1,
                        'email' => 'johndoe@domain.com'
                    ]
                        ], "5U3Gn3gp4LrQpS34d7Gj", "HS512");
        return $this->response->withJson(['token' => $token]);
    });
});
