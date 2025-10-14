<?php

namespace Core;

use Core\Http\Request;
use Core\Http\Response;

class Controller
{
    protected Request $request;

    protected function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function render(string $view, array $data = [])
    {
        extract($data);
        include VIEW_PATH . "/$view.php";
    }

    public function render_with_layout(
        string $view,
        array $data = [],
        string $layout = 'layouts/layout',
    ): Response {
        extract($data);
        ob_start();
        include VIEW_PATH . "/$view.php";
        $content = ob_get_clean();
        ob_start();
        include VIEW_PATH . "/$layout.php";
        $response = new Response();
        $response->body(ob_get_clean());
        return $response;
    }
}
