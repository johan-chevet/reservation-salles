<?php

namespace Src\Controllers;

use Core\Controller;
use core\Request;
use Core\SessionManager;
use Src\Models\User;

class AuthController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function register(Request $request)
    {
        $errors = [];

        if ($request->method === 'POST') {
            $login = trim($request->post['login'] ?? '');
            $password = trim($request->post['password'] ?? '');
            $password_confirm = trim($request->post['password-confirmation'] ?? '');
            if (empty($login)) {
                $errors['login'] = "Veuillez renseigner votre login";
            }

            if (empty($password)) {
                $errors['password'] = "Veuillez renseigner votre mot de passe";
            }

            if ($password !== $password_confirm) {
                $errors['password-confirmation'] = "Les mots de passe ne correspondent pas";
            }

            if (empty($errors)) {
                $user = User::find_by_login($login);
                if (!$user) {
                    $user = new User();
                    $user->login = $request->post['login'];
                    $user->password = password_hash($request->post['password'], PASSWORD_BCRYPT);
                    $user->save();
                    redirect('/login');
                }
                $errors['login'] = "Nom de login indisponible";
            }
        }
        $this->render_with_layout('auth/register', ['form' => $request->post, 'errors' => $errors]);
    }

    public function login(Request $request)
    {
        $errors = [];
        if ($request->method === 'POST') {
            $login = trim($request->post['login'] ?? '');
            $password = trim($request->post['password'] ?? '');
            if (empty($login)) {
                $errors['login'] = "Veuillez renseigner votre login";
            }

            if (empty($password)) {
                $errors['password'] = "Veuillez renseigner votre mot de passe";
            }
            if (empty($errors)) {
                $user = User::find_by_login($login);
                if ($user && password_verify($password, $user->password)) {
                    SessionManager::setUser($user);
                    redirect('/');
                }
                $errors['password'] = "Login ou mot de passe incorrect";
            }
        }
        $this->render_with_layout('auth/login', ['form' => $request->post, 'errors' => $errors]);
    }

    public function logout()
    {
        if (SessionManager::is_logged()) {
            SessionManager::destroy();
            redirect('login');
        }
    }
}
