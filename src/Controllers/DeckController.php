<?php
namespace OnePieceTCGCollect\src\Controllers;

use OnePieceTCGCollect\src\Models\Deck;
use OnePieceTCGCollect\src\Models\Card;

class DeckController
{
    public function create()
    {
       
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userId = $_SESSION['user']['id'];
            $name = $_POST['name'];
            $leaderId = $_POST['leader_id'];

            $deckId = Deck::create($userId, $name, $leaderId);

            header("Location: index.php?controller=deck&action=edit&id=" . $deckId);
            exit;
        }

        $leaders = Card::getByType('Leader');

        ob_start();
        require __DIR__ . '/../../views/deck/create.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../views/layout.php';
    }

    public function edit()
    {
       
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $deckId = $_GET['id'];
        $deck = Deck::getDeck($deckId);
        $leader = Card::getById($deck['leader_id']); // On récupère la couleur du leader

        // Liste des cartes filtrées par couleur du leader
        $cards = Card::getByColor($leader['color']);

        ob_start();
        require __DIR__ . '/../../views/deck/edit.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../views/layout.php';
    }

    public function addCard()
    {
        
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        $deckId = $_POST['deck_id'];
        $cardId = $_POST['card_id'];
        $quantity = (int) $_POST['quantity'];

        $deck = Deck::getDeck($deckId);
        $leader = Card::getById($deck['leader_id']);
        $card = Card::getById($cardId);

        // Vérif couleur
        if ($card['color'] !== $leader['color']) {
            $_SESSION['flash'] = "Erreur : La carte n’a pas la même couleur que le leader.";
            header("Location: index.php?controller=deck&action=edit&id=$deckId");
            exit;
        }

        // Vérif limite de 50 cartes
        $currentCount = Deck::countCards($deckId);
        if ($currentCount + $quantity > 50) {
            $_SESSION['flash'] = "Erreur : Un deck doit contenir exactement 50 cartes (hors Leader).";
            header("Location: index.php?controller=deck&action=edit&id=$deckId");
            exit;
        }

        Deck::addCard($deckId, $cardId, $quantity);

        header("Location: index.php?controller=deck&action=edit&id=$deckId");
        exit;
    }

    public function removeCard()
{
    session_start();
    if (!isset($_SESSION['user'])) {
        header('Location: index.php?controller=auth&action=login');
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $deckId = (int)$_POST['deck_id'];
        $cardId = (int)$_POST['card_id'];

        \OnePieceTCGCollect\src\Models\Deck::removeCard($deckId, $cardId, 1);
    }

    header("Location: index.php?controller=deck&action=edit&id=$deckId");
    exit;
}

    public function list()
{
   
    if (!isset($_SESSION['user'])) {
        header("Location: index.php?controller=auth&action=login");
        exit;
    }

    $userId = $_SESSION['user']['id'];
    $decks = \OnePieceTCGCollect\src\Models\Deck::getByUserWithCards($userId);

    ob_start();
    require __DIR__ . '/../../views/deck/list.php';
    $content = ob_get_clean();

    require __DIR__ . '/../../views/layout.php';
}

}
