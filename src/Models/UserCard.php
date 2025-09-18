<?php
namespace OnePieceTCGCollect\src\Models;

use OnePieceTCGCollect\src\Core\Database;

class UserCard
{
   public static function getUserCollection($userId)
{
    $db = Database::getInstance();

    $stmt = $db->prepare("
        SELECT c.id, c.name, c.type, c.color,c.price, c.Life, c.extension,c.version ,  c.rarity, uc.quantity
        FROM user_cards uc
        JOIN cards c ON uc.card_id = c.id
        WHERE uc.user_id = ?
    ");

    $stmt->execute([$userId]);

    return $stmt->fetchAll();
}


    public static function addCardToCollection($userId, $cardId)
{
    $db = \OnePieceTCGCollect\src\Core\Database::getInstance();
    $stmt = $db->prepare("INSERT INTO user_cards (user_id, card_id) VALUES (:user_id, :card_id)");
    $stmt->execute([
        'user_id' => $userId,
        'card_id' => $cardId
    ]);
}

public static function getCardInCollection(int $userId, int $cardId)
{
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM user_cards WHERE user_id = ? AND card_id = ?");
    $stmt->execute([$userId, $cardId]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

public static function incrementQuantity(int $userId, int $cardId)
{
    $db = Database::getInstance();
    $stmt = $db->prepare("UPDATE user_cards SET quantity = quantity + 1 WHERE user_id = ? AND card_id = ?");
    return $stmt->execute([$userId, $cardId]);
}

public static function removeCardFromCollection(int $userId, int $cardId, int $quantity)
{
    $db = Database::getInstance();

    // Vérifie la quantité actuelle
    $stmt = $db->prepare("SELECT quantity FROM user_cards WHERE user_id = ? AND card_id = ?");
    $stmt->execute([$userId, $cardId]);
    $card = $stmt->fetch(\PDO::FETCH_ASSOC);

    if ($card) {
        $currentQuantity = (int) $card['quantity'];

        if ($quantity >= $currentQuantity) {
            // Si on retire tout ou plus → on supprime la ligne
            $stmt = $db->prepare("DELETE FROM user_cards WHERE user_id = ? AND card_id = ?");
            return $stmt->execute([$userId, $cardId]);
        } else {
            // Sinon, on décrémente
            $stmt = $db->prepare("UPDATE user_cards SET quantity = quantity - ? WHERE user_id = ? AND card_id = ?");
            return $stmt->execute([$quantity, $userId, $cardId]);
        }
    }

    return false;
}


}
