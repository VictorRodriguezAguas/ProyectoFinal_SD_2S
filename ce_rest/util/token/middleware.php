<?php
// Application middleware

// e.g: $app->add(new \Slim\Csrf\Guard);

$app->add(new Tuupola\Middleware\JwtAuthentication([
    "path"      => ["/"],
    "ignore"    => ["/login/","/persona/insertar"],
    "secure"    => false,
    "header"    => TOKEN,
    "regexp"    => "/(.*)/",
    "secret"    => KEYEPICO,
    "algorithm" => [ALGORITMO],
    //"logger"    => $container->get("logger"),
    "attribute" => "jwt"
]));