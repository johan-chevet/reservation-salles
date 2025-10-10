<?php

use Src\Controllers\AuthController;
use Src\Controllers\HomeController;
use Src\Controllers\ProfileController;
use Src\Controllers\ReservationController;
use Src\Middlewares\GuestMiddleware;
use Src\Middlewares\LoggedInMiddleware;

//TODO guards
const ROUTES = [
    '/' => [
        'controller' => HomeController::class,
        'method' => 'index'
    ],
    '/register' => [
        'controller' => AuthController::class,
        'method' => 'register',
        'middlewares' => [new GuestMiddleware()]
    ],
    '/login' => [
        'controller' => AuthController::class,
        'method' => 'login',
        'middlewares' => [new GuestMiddleware()]
    ],
    '/logout' => [
        'controller' => AuthController::class,
        'method' => 'logout',
        'middlewares' => [new LoggedInMiddleware()]
    ],
    '/profile/update' => [
        'controller' => ProfileController::class,
        'method' => 'update',
        'middlewares' => [new LoggedInMiddleware()]
    ],
    'planning' => [
        'controller' => ReservationController::class,
        'method' => 'show_planning'
    ],
    'reserve' => [
        'controller' => ReservationController::class,
        'method' => 'reserve'
    ]
];
