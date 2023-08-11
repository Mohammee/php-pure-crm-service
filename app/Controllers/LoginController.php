<?php

namespace App\Controllers;

use App\Models\User;
use App\View;

class LoginController
{

    protected ?User $user = null;

    public function __construct()
    {
        $this->user = new User();
    }

    public function loginForm()
    {
        return View::make('auth.login');
    }

    public function login()
    {
        if (isset($_SESSION['auth'])) {
            $user = ($_SESSION['auth']);
            header("Location: http://localhost:8000/" . $user['role'], true ,302); // Redirect to a logged-in user page
            exit();
        }

        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$email || !$password) {
            throw new \Exception('Something Error.');
        }

        if ($user = $this->user->findByEmail($email)) {
            if (password_verify($password, $user['password'])) {
                $_SESSION['auth'] = ['id' => $user->id, 'role' => $user->role];
                header('Location: http://localhost:8000/'. $user['role'], true, 302);
                exit;
            }
        }

        throw new \Exception('Something Error.');
    }

    public function logout()
    {
//        session_destroy();
        session_unset();
        session_destroy();

        session_regenerate_id(true);
        setcookie(session_name(), session_id(), time() + 60 * 30, '/');

//        var_dump($_SESSION);die;
        header("Location: http://localhost:8000/login");
        exit;
    }
}