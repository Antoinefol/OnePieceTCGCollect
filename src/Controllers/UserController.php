<?php
namespace OnePieceTCGCollect\src\Controllers;

use OnePieceTCGCollect\src\Models\UserCard;
use OnePieceTCGCollect\src\Models\User;

class UserController
{
    public function profile()
{
    

    if (!isset($_SESSION['user'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    $userId = $_SESSION['user']['id']; 
    $user = User::findById($userId);
    $cards = UserCard::getUserCollection($userId);

    ob_start();
    require __DIR__ . '/../../views/user/profile.php';
    $content = ob_get_clean();

     require __DIR__ . '/../../views/layout.php';
}

}
