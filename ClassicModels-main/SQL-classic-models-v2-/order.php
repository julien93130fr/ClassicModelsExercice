<?php
// Chargement des librairies
require_once './lib/debug.php';
require_once './lib/format.php';
require_once './lib/route.php';

// Chargement du modèle
require_once './model/database.php';
require_once './model/customers.php';
require_once './model/orders.php';


// Si on a recu un ID dans l URL
if (isset($_GET['id'])) {

    // On récupère cet ID
    $id = $_GET['id'];
} else {
    // Sinon on retourne sur la page d ' acceuil
    redirect('index.php');
}

// Charge les données de la commande
$order = getOrder($id);
// d($order);

// Si la commande n existe pas
if(empty($order)) {

    // On retourne sur la page d' acceuil
    redirect('index.php');
}

// Récupère toutes les lignes de la commande
$orderDetails = getOrdersDetails($id);
//d($orderDetails);


// Chargement du template de la page
include './templates/order.phtml';

