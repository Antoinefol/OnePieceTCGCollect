<?php
namespace OnePieceTCGCollect\src\Models;

use OnePieceTCGCollect\src\Core\Database;

class Card
{
    public static function getAll()
    {
        $db = Database::getInstance();
        $stmt = $db->query("SELECT * FROM cards");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    public static function getWithPrice($cardId)
{
    $db = \OnePieceTCGCollect\src\Core\Database::getInstance();
    $stmt = $db->prepare("SELECT * FROM cards WHERE id = ?");
    $stmt->execute([$cardId]);
    $card = $stmt->fetch();

    if ($card) {
        $price = \OnePieceTCGCollect\src\Services\EbayApi::getLastSoldPrice($card['name']);
        $card['price'] = $price ?? 'Non disponible';
    }

    return $card;
}

public static function getFiltered(array $filters)
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

    return $stmt->fetchAll(\PDO::FETCH_ASSOC);
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


}
