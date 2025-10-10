<?php

namespace Src\Controllers;

use Core\Controller;

class HomeController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->render_with_layout('home/index');
    }
}
