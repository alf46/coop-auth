<?php

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Psr7\Response;
use Slim\Routing\RouteContext;

require_once "acl.php";

class AuthMiddleware
{
    private IUserService $us;

    function __construct()
    {
        $this->us = new UserService();
    }

    public function __invoke(Request $req, RequestHandler $handler): Response
    {
        $headers = getallheaders();
        if (!isset($headers["Authorization"])) {
            http_response_code(401);
            exit;
        }

        $token = str_replace("Bearer ", '', $headers["Authorization"]);

        // Validar token
        $auth = new Auth();
        $payload = $auth->validateToken($token);
        if (!$payload) {
            http_response_code(401);
            exit;
        }

        // Si este endpoint es solo para admin se debe validar con el rol del usuarios.
        if ($this->isAdmin($req) && $payload->role != "adm") {
            http_response_code(403);
            exit;
        }

        $_SESSION['sub'] = $payload->sub;
        $_SESSION['email'] = $payload->email;
        $_SESSION['role'] = $payload->role;
        return $handler->handle($req);
    }

    private function isAdmin($req)
    {
        $routeContext = RouteContext::fromRequest($req);
        $route = $routeContext->getRoute();
        $value = $_SERVER['REQUEST_METHOD'] . " " . $route->getPattern();
        global $acl;
        if (in_array($value, $acl)) {
            return true;
        }
        return false;
    }
}