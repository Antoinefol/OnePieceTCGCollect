<?php
namespace OnePieceTCGCollect\src\Models;

use OnePieceTCGCollect\src\Core\Database;

class Deck
{
    public static function create($userId, $name, $leaderId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("INSERT INTO decks (user_id, name, leader_id) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $name, $leaderId]);
        return $db->lastInsertId();
    }

    public static function addCard($deckId, $cardId, $quantity)
    {
        $db = Database::getInstance();

        // Vérifie si déjà présente
        $stmt = $db->prepare("SELECT quantity FROM deck_cards WHERE deck_id = ? AND card_id = ?");
        $stmt->execute([$deckId, $cardId]);
        $row = $stmt->fetch();

        if ($row) {
            $newQuantity = $row['quantity'] + $quantity;
            if ($newQuantity > 4) {
                $newQuantity = 4; // max 4 copies
            }
            $stmt = $db->prepare("UPDATE deck_cards SET quantity = ? WHERE deck_id = ? AND card_id = ?");
            $stmt->execute([$newQuantity, $deckId, $cardId]);
        } else {
            if ($quantity > 4) $quantity = 4;
            $stmt = $db->prepare("INSERT INTO deck_cards (deck_id, card_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$deckId, $cardId, $quantity]);
        }
    }

    public static function getDeck($deckId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM decks WHERE id = ?");
        $stmt->execute([$deckId]);
        $deck = $stmt->fetch(\PDO::FETCH_ASSOC);

        $stmt = $db->prepare("SELECT c.*, dc.quantity 
                              FROM deck_cards dc 
                              JOIN cards c ON c.id = dc.card_id 
                              WHERE dc.deck_id = ?");
        $stmt->execute([$deckId]);
        $deck['cards'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        return $deck;
    }

    public static function getByUser($userId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM decks WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function countCards($deckId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT SUM(quantity) as total FROM deck_cards WHERE deck_id = ?");
        $stmt->execute([$deckId]);
        return (int) $stmt->fetchColumn();
    }

    public static function getByUserWithCards($userId)
{
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM decks WHERE user_id = ?");
    $stmt->execute([$userId]);
    $decks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    foreach ($decks as &$deck) {
        $stmt = $db->prepare("SELECT COUNT(*) FROM deck_cards WHERE deck_id = ?");
        $stmt->execute([$deck['id']]);
        $deck['card_count'] = (int) $stmt->fetchColumn();
    }

    return $decks;
}

public static function removeCard(int $deckId, int $cardId, int $quantity = 1): void
{
    $db = Database::getInstance();

    // Vérifie si la carte est dans le deck
    $stmt = $db->prepare("SELECT quantity FROM deck_cards WHERE deck_id = :deck AND card_id = :card");
    $stmt->execute([':deck' => $deckId, ':card' => $cardId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        $newQty = (int)$row['quantity'] - $quantity;
        if ($newQty > 0) {
            $stmt = $db->prepare("UPDATE deck_cards SET quantity = :q WHERE deck_id = :deck AND card_id = :card");
            $stmt->execute([':q' => $newQty, ':deck' => $deckId, ':card' => $cardId]);
        } else {
            $stmt = $db->prepare("DELETE FROM deck_cards WHERE deck_id = :deck AND card_id = :card");
            $stmt->execute([':deck' => $deckId, ':card' => $cardId]);
        }
    }
}

}
