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
            $name = trim($_POST['name'] ?? '');
            $leaderId = $_POST['leader_id'] ?? '';

            if ($name === '' || $leaderId === '') {
                $_SESSION['flash'] = "Nom ou leader manquant.";
                header("Location: index.php?controller=deck&action=create");
                exit;
            }

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

        $deckId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        $deck = Deck::getDeck($deckId);
        if (!$deck) {
            $_SESSION['flash'] = "Deck introuvable.";
            header("Location: index.php?controller=deck&action=list");
            exit;
        }

        // verification propriétaire
        if ($deck['user_id'] != $_SESSION['user']['id']) {
            $_SESSION['flash'] = "Accès refusé.";
            header("Location: index.php?controller=deck&action=list");
            exit;
        }

        $leader = Card::getById($deck['leader_id']);
        if (!$leader) {
            $_SESSION['flash'] = "Leader introuvable.";
            header("Location: index.php?controller=deck&action=list");
            exit;
        }

        // Gestion multi-couleurs (ex: "Purple/Green")
        $colors = array_map('trim', explode('/', $leader['color'] ?? ''));
        $cards = Card::getByColors($colors); // assure-toi que cette méthode existe

        ob_start();
        require __DIR__ . '/../../views/deck/edit.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../views/layout.php';
    }

    public function addCard()
    {
       
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=deck&action=list');
            exit;
        }

        $deckId = isset($_POST['deck_id']) ? (int)$_POST['deck_id'] : 0;
        $cardId = isset($_POST['card_id']) ? (string)$_POST['card_id'] : '';
        $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

        $deck = Deck::getDeck($deckId);
        if (!$deck) {
            $_SESSION['flash'] = "Deck introuvable.";
            header("Location: index.php?controller=deck&action=list");
            exit;
        }

        // sécurité : vérifier que l'utilisateur est le propriétaire
        if ($deck['user_id'] != $_SESSION['user']['id']) {
            $_SESSION['flash'] = "Accès refusé.";
            header("Location: index.php?controller=deck&action=list");
            exit;
        }

        $leader = Card::getById($deck['leader_id']);
        $card = Card::getById($cardId);

        if (!$leader || !$card) {
            $_SESSION['flash'] = "Carte ou leader introuvable.";
            header("Location: index.php?controller=deck&action=edit&id=$deckId");
            exit;
        }

        // Empêcher l'ajout d'un Leader via POST
        if (($card['type'] ?? '') === 'Leader') {
            $_SESSION['flash'] = "Erreur : Impossible d’ajouter un Leader dans le deck.";
            header("Location: index.php?controller=deck&action=edit&id=$deckId");
            exit;
        }

        // Vérif couleur(s) du leader (multi-couleurs possible)
        $leaderColors = array_map('trim', explode('/', $leader['color'] ?? ''));
        if (!in_array($card['color'], $leaderColors, true)) {
            $_SESSION['flash'] = "Erreur : La carte doit être de la même couleur que le leader.";
            header("Location: index.php?controller=deck&action=edit&id=$deckId");
            exit;
        }

        // Utilise la méthode addCard du modèle (qui gère règles 4 copies / 50 cartes)
        $res = Deck::addCard($deckId, $cardId, $quantity);

        if (!$res['ok']) {
            $_SESSION['flash'] = $res['msg'] ?? "Impossible d'ajouter la carte.";
        } else {
            if (($res['added'] ?? 0) < ($res['requested'] ?? $quantity)) {
                $_SESSION['flash'] = "Ajout partiel : ajouté {$res['added']} (sur {$res['requested']}).";
            } else {
                $_SESSION['flash'] = "Carte ajoutée ({$res['added']}).";
            }
        }

        header("Location: index.php?controller=deck&action=edit&id=$deckId");
        exit;
    }

    public function removeCard()
    {
      
        if (!isset($_SESSION['user'])) {
            header('Location: index.php?controller=auth&action=login');
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: index.php?controller=deck&action=list');
            exit;
        }

        $deckId = isset($_POST['deck_id']) ? (int)$_POST['deck_id'] : 0;
        $cardId = isset($_POST['card_id']) ? (string)$_POST['card_id'] : '';
        $quantity = isset($_POST['quantity']) ? max(1, (int)$_POST['quantity']) : 1;

        $deck = Deck::getDeck($deckId);
        if (!$deck) {
            $_SESSION['flash'] = "Deck introuvable.";
            header("Location: index.php?controller=deck&action=list");
            exit;
        }

        // sécurité : vérifier que l'utilisateur est le propriétaire
        if ($deck['user_id'] != $_SESSION['user']['id']) {
            $_SESSION['flash'] = "Accès refusé.";
            header("Location: index.php?controller=deck&action=list");
            exit;
        }

        $res = Deck::removeCard($deckId, $cardId, $quantity);
        if (!$res['ok']) {
            $_SESSION['flash'] = $res['msg'] ?? "Impossible de retirer la carte.";
        } else {
            $_SESSION['flash'] = "Carte retirée. Quantité restante : " . ($res['newQty'] ?? 0);
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
        $decks = Deck::getByUserWithCards($userId);

        ob_start();
        require __DIR__ . '/../../views/deck/list.php';
        $content = ob_get_clean();

        require __DIR__ . '/../../views/layout.php';
    }

    public function delete()
    {
       
        if (!isset($_SESSION['user'])) {
            header("Location: index.php?controller=auth&action=login");
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $deckId = (int) $_POST['deck_id'];
            $userId = $_SESSION['user']['id'];

            \OnePieceTCGCollect\src\Models\Deck::delete($deckId, $userId);
        }

        header("Location: index.php?controller=deck&action=list");
        exit;
    }
}
