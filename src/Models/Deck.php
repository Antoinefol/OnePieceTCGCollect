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

    public static function getDeck($deckId)
{
    $db = Database::getInstance();

    $stmt = $db->prepare("SELECT * FROM decks WHERE id = ?");
    $stmt->execute([$deckId]);
    $deck = $stmt->fetch(\PDO::FETCH_ASSOC);
    if (!$deck) return null;

    // Récupère les cartes du deck avec la quantity
    $stmt = $db->prepare("
        SELECT c.*, dc.quantity
        FROM deck_cards dc
        JOIN cards c ON c.id = dc.card_id
        WHERE dc.deck_id = ?
    ");
    $stmt->execute([$deckId]);
    $deck['cards'] = $stmt->fetchAll(\PDO::FETCH_ASSOC);

    // Calcule le nombre total réel de cartes (somme des quantities)
    $stmt = $db->prepare("SELECT COALESCE(SUM(quantity),0) as total FROM deck_cards WHERE deck_id = ?");
    $stmt->execute([$deckId]);
    $deck['card_count'] = (int) $stmt->fetchColumn();

    // 💰 Calcule la valeur totale du deck
    $totalValue = 0;
    foreach ($deck['cards'] as $card) {
        if (is_numeric($card['price'])) {
            $totalValue += (float)$card['price'] * (int)$card['quantity'];
        }
    }
    $deck['total_value'] = $totalValue;

    return $deck;
}


    public static function getByUser($userId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM decks WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public static function getByUserWithCards($userId)
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM decks WHERE user_id = ?");
        $stmt->execute([$userId]);
        $decks = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        foreach ($decks as &$deck) {
            $deck['card_count'] = self::countCards($deck['id']);
        }

        return $decks;
    }

    /**
     * Retourne la somme des quantities (nombre réel de cartes)
     */
    public static function countCards(int $deckId): int
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT COALESCE(SUM(quantity),0) as total FROM deck_cards WHERE deck_id = ?");
        $stmt->execute([$deckId]);
        return (int) $stmt->fetchColumn();
    }

    /**
     * Ajoute une carte en respectant les règles :
     * - max 4 copies de la même carte
     * - max 50 cartes dans le deck
     */
    public static function addCard(int $deckId, string $cardId, int $quantity = 1): array
    {
        $db = Database::getInstance();

        $currentTotal = self::countCards($deckId);

        // Vérifie déjà présente
        $stmt = $db->prepare("SELECT quantity FROM deck_cards WHERE deck_id = ? AND card_id = ?");
        $stmt->execute([$deckId, $cardId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        $existingQty = $row ? (int)$row['quantity'] : 0;

        // limites
        $maxAddForThisCard = 4 - $existingQty;
        if ($maxAddForThisCard <= 0) {
            return ['ok' => false, 'msg' => 'Vous avez déjà 4 exemplaires de cette carte.'];
        }

        $maxAddForDeck = 50 - $currentTotal;
        if ($maxAddForDeck <= 0) {
            return ['ok' => false, 'msg' => 'Le deck contient déjà 50 cartes.'];
        }

        $toAdd = min($quantity, $maxAddForThisCard, $maxAddForDeck);

        if ($row) {
            $newQty = $existingQty + $toAdd;
            $stmt = $db->prepare("UPDATE deck_cards SET quantity = ? WHERE deck_id = ? AND card_id = ?");
            $stmt->execute([$newQty, $deckId, $cardId]);
        } else {
            $stmt = $db->prepare("INSERT INTO deck_cards (deck_id, card_id, quantity) VALUES (?, ?, ?)");
            $stmt->execute([$deckId, $cardId, $toAdd]);
        }

        return ['ok' => true, 'added' => $toAdd, 'requested' => $quantity];
    }

    /**
     * Supprime une carte (ou diminue sa quantité)
     */
    public static function removeCard(int $deckId, string $cardId, int $quantity = 1): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT quantity FROM deck_cards WHERE deck_id = ? AND card_id = ?");
        $stmt->execute([$deckId, $cardId]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return ['ok' => false, 'msg' => 'Carte non trouvée dans le deck'];
        }

        $current = (int) $row['quantity'];
        $newQty = $current - $quantity;

        if ($newQty > 0) {
            $stmt = $db->prepare("UPDATE deck_cards SET quantity = ? WHERE deck_id = ? AND card_id = ?");
            $stmt->execute([$newQty, $deckId, $cardId]);
            return ['ok' => true, 'newQty' => $newQty];
        } else {
            $stmt = $db->prepare("DELETE FROM deck_cards WHERE deck_id = ? AND card_id = ?");
            $stmt->execute([$deckId, $cardId]);
            return ['ok' => true, 'newQty' => 0];
        }
    }

    public static function delete(int $deckId, int $userId): bool
{
    $db = Database::getInstance();

    // Supprime d'abord les cartes du deck
    $stmt = $db->prepare("DELETE FROM deck_cards WHERE deck_id = ?");
    $stmt->execute([$deckId]);

    // Supprime ensuite le deck lui-même (vérifie que l'user est bien le propriétaire)
    $stmt = $db->prepare("DELETE FROM decks WHERE id = ? AND user_id = ?");
    return $stmt->execute([$deckId, $userId]);
}

}
