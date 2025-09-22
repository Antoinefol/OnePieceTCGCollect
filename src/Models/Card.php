<?php
namespace OnePieceTCGCollect\src\Models;

use OnePieceTCGCollect\src\Core\Database;
use PDO;

class Card
{
    public static function getAll(): array
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM cards");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getWithPrice($cardId): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM cards WHERE id = ?");
        $stmt->execute([$cardId]);
        $card = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($card) {
            $price = \OnePieceTCGCollect\src\Services\EbayApi::getLastSoldPrice($card['name']);
            $card['price'] = $price ?? 'Non disponible';
        }

        return $card ?: null;
    }

    public static function getFiltered(array $filters): array
    {
        $db = Database::getInstance();
        $query = "SELECT * FROM cards WHERE 1=1";
        $params = [];

        if (!empty($filters['search'])) {
            $query .= " AND name LIKE :search";
            $params[':search'] = "%" . $filters['search'] . "%";
        }

        if (!empty($filters['color'])) {
            $query .= " AND color = :color";
            $params[':color'] = $filters['color'];
        }

        if (!empty($filters['extension'])) {
            $query .= " AND extension = :extension";
            $params[':extension'] = $filters['extension'];
        }

        if (!empty($filters['type'])) {
            $query .= " AND type = :type";
            $params[':type'] = $filters['type'];
        }

        if (!empty($filters['sort'])) {
            if ($filters['sort'] === 'price_asc') {
                $query .= " ORDER BY price ASC";
            } elseif ($filters['sort'] === 'price_desc') {
                $query .= " ORDER BY price DESC";
            }
        }

        $stmt = $db->prepare($query);
        $stmt->execute($params);

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function updatePriceAndUrl(int $id, float $price, string $url): void
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("UPDATE cards SET price = :price, url = :url WHERE id = :id");
        $stmt->execute([
            ':price' => $price,
            ':url'   => $url,
            ':id'    => $id,
        ]);
    }

    /** 🔽 Fonctions ajoutées pour les decks **/

    public static function getById(string $id): ?array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM cards WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row === false ? null : $row;
    }

    public static function getByType(string $type): array
    {
        $db = Database::getInstance();
        $stmt = $db->prepare("SELECT * FROM cards WHERE type = :type ORDER BY name ASC");
        $stmt->execute([':type' => $type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getByColors(array $colors): array
{
    $db = Database::getInstance();

    // Construction dynamique de la clause IN
    $in  = str_repeat('?,', count($colors) - 1) . '?';
    $sql = "SELECT * FROM cards WHERE color IN ($in) AND type != 'Leader'";

    $stmt = $db->prepare($sql);
    $stmt->execute($colors);

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

public static function getByColorWithoutLeaders(string $color): array
{
    $db = Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM cards WHERE color = :color AND type != 'Leader'");
    $stmt->execute([':color' => $color]);
    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
}

    public static function search(string $keyword): array
    {
        $db = Database::getInstance();
        $sql = "SELECT * FROM cards 
                WHERE name LIKE :k OR number LIKE :k OR rarity LIKE :k OR color LIKE :k
                ORDER BY name ASC";
        $stmt = $db->prepare($sql);
        $stmt->execute([':k' => '%' . $keyword . '%']);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
