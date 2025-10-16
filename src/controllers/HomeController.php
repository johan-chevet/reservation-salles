<?php

namespace Src\Controllers;

use Core\Controller;
use Core\Http\Request;
use Core\Validator;

class HomeController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function index()
    {
        $form = [
            'id' => 3,
            'name' => '   d',
        ];
        // var_dump(is_int($form['id']));
        // var_dump($form);
        $validator = new Validator($form);
        $validator
            // ->add('id')
            // ->required()
            // ->is_int()
            // ->greater_than(2)
            ->add('name')
            ->required()
            ->validate();
        var_dump($validator->get_errors());
        return $this->render_with_layout('home/index');
    }
}
