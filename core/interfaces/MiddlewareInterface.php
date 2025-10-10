<?php

namespace Core\Interfaces;

use Core\Request;

interface MiddlewareInterface
{
    public function __invoke(Request $request);
}
