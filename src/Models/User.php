<?php
namespace OnePieceTCGCollect\src\Models;
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);




use OnePieceTCGCollect\src\Core\Database;

class User
{
    public static function create($username, $email, $password)
    {
        try {
            $pdo = Database::getInstance();
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
            $stmt->execute([$username, $email, $password]);
        } catch (\PDOException $e) {
            die("Erreur SQL : " . $e->getMessage());
        }
    }

    public static function findByEmail($email)
    {
        $pdo = Database::getInstance();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    public static function findById($id)
{
    $pdo = Database::getInstance();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}
}

