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

// Charge les données du client
$customer = getCustomer($id);

// Si le client n' existe pas 
if(empty($customer)) {

    // On retourne sur la page d' acceuil
    redirect('index.php');
}

// Récupère toutes les commandes du client
$orders = getOrdersByCustomerNumber($id);

// Chargement du template de la page
include './templates/customer.phtml';