<?php

namespace Src\Middlewares;

use Core\Interfaces\MiddlewareInterface;
use Core\Request;
use Core\SessionManager;

class LoggedInMiddleware implements MiddlewareInterface
{
    public function __invoke(Request $request)
    {
        if (!SessionManager::is_logged()) {
            redirect('login', 401);
        }
    }
}
