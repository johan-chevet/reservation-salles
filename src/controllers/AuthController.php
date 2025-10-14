<?php

namespace Src\Controllers;

use Core\Controller;
use Core\Http\Request;
use Core\Http\Response;
use Core\SessionManager;
use Src\Models\User;

class AuthController extends Controller
{
    public function __construct(Request $request)
    {
        parent::__construct($request);
    }

    public function register()
    {
        $errors = [];

        if ($this->request->method === 'POST') {
            $login = trim($this->request->post['login'] ?? '');
            $password = trim($this->request->post['password'] ?? '');
            $password_confirm = trim($this->request->post['password-confirmation'] ?? '');
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
                    $user->login = $this->request->post['login'];
                    $user->password = password_hash($this->request->post['password'], PASSWORD_BCRYPT);
                    $user->save();
                    redirect('/login');
                }
                $errors['login'] = "Nom de login indisponible";
            }
        }
        return $this->render_with_layout('auth/register', ['form' => $this->request->post, 'errors' => $errors]);
    }

    public function login()
    {
        $errors = [];
        if ($this->request->method === 'POST') {
            $login = trim($this->request->post['login'] ?? '');
            $password = trim($this->request->post['password'] ?? '');
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
        return $this->render_with_layout('auth/login', ['form' => $this->request->post, 'errors' => $errors]);
    }

    public function logout()
    {
        if (SessionManager::is_logged()) {
            SessionManager::destroy();
            return (new Response())->redirect('login');
        }
    }
}
