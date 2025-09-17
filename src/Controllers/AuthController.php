<?php
namespace OnePieceTCGCollect\src\Controllers;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);


use OnePieceTCGCollect\src\Models\User;

class AuthController
{
    public function register()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars($_POST['username']);
            $email = htmlspecialchars($_POST['email']);
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            User::create($username, $email, $password);

            header('Location: index.php?controller=auth&action=login');
            exit;
        }

            ob_start();
    require __DIR__ . '/../../views/auth/register.php';
    $content = ob_get_clean();

     require __DIR__ . '/../../views/layout.php';
    }

    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = htmlspecialchars($_POST['email']);
            $password = $_POST['password'];

            $user = User::findByEmail($email);

            if ($user && password_verify($password, $user['password'])) {
                session_start();
                $_SESSION['user'] = [
                    'id' => $user['id'],
                    'username' => $user['username'],
                    'email' => $user['email']
                ];
                header('Location: index.php?controller=user&action=profile');;
                exit;
            } else {
                echo "Email ou mot de passe incorrect.";
            }
        } else {
            ob_start();
        require __DIR__ . '/../../views/auth/login.php';
        $content = ob_get_clean();

         require __DIR__ . '/../../views/layout.php';
        }
    }

    public function profile()
    {
        session_start();
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        $username = $_SESSION['user']['username'];
        ob_start();
        require __DIR__ . '/../../views/user/profile.php';
         $content = ob_get_clean();

        require __DIR__ . '/../../views/layout.php';
    }
}
