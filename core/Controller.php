<?php

namespace Core;

class Controller
{
    // protected Request $request;

    protected function __construct()
    {
        // $this->request = new Request();
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
    ) {
        extract($data);
        ob_start();
        include VIEW_PATH . "/$view.php";
        $content = ob_get_clean();
        include VIEW_PATH . "/$layout.php";
    }
}
