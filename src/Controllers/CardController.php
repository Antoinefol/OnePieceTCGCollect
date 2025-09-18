<?php
namespace OnePieceTCGCollect\src\Controllers;

use OnePieceTCGCollect\src\Models\Card;

class CardController
{
public function list()
{
    $filters = [
        'search'    => $_GET['search']    ?? null,
        'color'     => $_GET['color']     ?? null,
        'extension' => $_GET['extension'] ?? null,
        'type'      => $_GET['type']      ?? null,
        'sort'      => $_GET['sort']      ?? null,
    ];

    $cards = \OnePieceTCGCollect\src\Models\Card::getFiltered($filters);

    ob_start();
    require __DIR__ . '/../../views/card/list.php';
    $content = ob_get_clean();

    require __DIR__ . '/../../views/layout.php';
}



public function addToCollection()
{
    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_id'])) {
        $userId = $_SESSION['user']['id'];
        $cardId = (int) $_POST['card_id'];

        // Vérifie si la carte existe déjà dans la collection
        $existing = \OnePieceTCGCollect\src\Models\UserCard::getCardInCollection($userId, $cardId);

        if ($existing) {
            // Carte déjà dans la collection → on incrémente
            \OnePieceTCGCollect\src\Models\UserCard::incrementQuantity($userId, $cardId);
        } else {
            // Carte absente → on l’ajoute avec quantité = 1
            \OnePieceTCGCollect\src\Models\UserCard::addCardToCollection($userId, $cardId);
        }

        header('Location: index.php?controller=card&action=list');
        exit;
    }
}


public function updatePrices()
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    // Récupère un éventuel id de départ dans l'URL (ex: updatePrices&start=50)
    $startId = isset($_GET['start']) ? (int) $_GET['start'] : 0;

    $cards = \OnePieceTCGCollect\src\Models\Card::getAll();

    foreach ($cards as $card) {
        // On saute les cartes en-dessous du startId
        if ($card['id'] < $startId) {
            continue;
        }

        $priceData = \OnePieceTCGCollect\src\Services\EbayApi::getLastSoldPrice(
            $card['version'],
            $card['number'],
            $card['extension'] ?? ''
        );

        if ($priceData) {
            \OnePieceTCGCollect\src\Models\Card::updatePriceAndUrl(
                $card['id'],
                $priceData['price'],
                $priceData['url']
            );
        }
    }

    $_SESSION['flash'] = "Les prix ont été mis à jour à partir de l'ID $startId";
    header('Location: index.php?controller=card&action=list');
    exit;
}

public function update()
{
    ob_start();
    require __DIR__ . '/../../views/card/update.php';
    $content = ob_get_clean();

    require __DIR__ . '/../../views/layout.php';
}

public function removeFromCollection()
{
    session_start();

    if (!isset($_SESSION['user'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['card_id'], $_POST['quantity'])) {
        $userId   = $_SESSION['user']['id'];
        $cardId   = (int) $_POST['card_id'];
        $quantity = (int) $_POST['quantity'];

        \OnePieceTCGCollect\src\Models\UserCard::removeCardFromCollection($userId, $cardId, $quantity);
    }

    header('Location: index.php?controller=user&action=profile');
    exit;
}

public function stats()
{
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    $userId = $_SESSION['user']['id'];

    // Récupérer la collection de l’utilisateur
    $cards = \OnePieceTCGCollect\src\Models\UserCard::getUserCollection($userId);

    // Charger les totaux par extension
    $extensionTotals = require __DIR__ . '/../../config/extensions.php';

    $extensions = [];
    $totalValue = 0;

    foreach ($cards as $card) {
        $extension = $card['extension'] ?? 'Inconnue';

        // Initialisation par extension
        if (!isset($extensions[$extension])) {
            $extensions[$extension] = [
                'unique'        => [],
                'types'         => [],
                'total_cards'   => $extensionTotals[$extension]['total'] ?? 0,
                'total_types'   => $extensionTotals[$extension]['types'] ?? [],
                'normal'        => 0,
                'parallel'      => 0,
                'normal_total'  => $extensionTotals[$extension]['normal'] ?? 0,
                'parallel_total'=> $extensionTotals[$extension]['parallel'] ?? 0,
            ];

            // Initialisation des types à 0
            foreach ($extensions[$extension]['total_types'] as $t => $count) {
                $extensions[$extension]['types'][$t] = 0;
            }
        }

        // Cartes uniques (sans doublons)
        $extensions[$extension]['unique'][$card['id']] = true;

        // Comptage des types
        $type = $card['type'] ?: 'Autre';
        if (isset($extensions[$extension]['types'][$type])) {
            $extensions[$extension]['types'][$type]++;
        } else {
            $extensions[$extension]['types'][$type] = 1;
        }

        // Normal / Parallèle basé sur la colonne version
        if (!empty($card['version']) && $card['version'] === 'Parallel') {
            $extensions[$extension]['parallel']++;
        } else {
            $extensions[$extension]['normal']++;
        }

        // Valeur totale
        if (is_numeric($card['price'])) {
            $totalValue += $card['price'] * (int)$card['quantity'];
        }
    }

    // Affichage
    ob_start();
    require __DIR__ . '/../../views/card/stats.php';
    $content = ob_get_clean();

    require __DIR__ . '/../../views/layout.php';
}

}
