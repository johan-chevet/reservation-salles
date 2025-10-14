<?php

namespace Src\Middlewares;

use Closure;
use Core\Http\Response;
use Core\Interfaces\MiddlewareInterface;
use Core\Http\Request;
use Core\SessionManager;

class LoggedInMiddleware implements MiddlewareInterface
{
    public function __invoke(Request $request, Closure $next): Response
    {
        if (!SessionManager::is_logged()) {
            $response = new Response();
            return $response->redirect('login', 401);
        }
        return $next($request);
    }
}
