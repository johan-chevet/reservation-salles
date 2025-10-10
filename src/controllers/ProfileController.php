<?php

namespace Src\Controllers;

use Core\Controller;
use core\Request;
use Core\SessionManager;
use Src\Models\User;

class ProfileController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function update(Request $request)
    {
        $errors = [];
        $user = User::find_by_id(SessionManager::get_user_id());
        if ($request->method === 'POST' && $user) {
            $login = trim($request->post['login'] ?? '');
            $password = trim($request->post['password'] ?? '');
            $password_confirm = trim($request->post['password-confirmation'] ?? '');

            if (empty($login)) {
                $errors['login'] = "Veuillez renseigner votre login";
            }

            if ($password !== $password_confirm) {
                $errors['password-confirmation'] = "Les mots de passe ne correspondent pas";
            }

            if (empty($errors)) {
                if ($user->login !== $login && User::find_by_login($login)) {
                    $errors['login'] = "Nom de login indisponible";
                } else {
                    $user->login = $login;
                }
            }

            if (!empty($errors) && !empty($password)) {
                $user->password = password_hash($password, PASSWORD_BCRYPT);
            }
            $user->save();
        }
        $this->render_with_layout('profile/update', [
            'form' => $request->post,
            'user' => $user,
            'errors' => $errors
        ]);
    }
}
