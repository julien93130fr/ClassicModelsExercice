<?php
// Chargement des librairies
require_once './lib/debug.php';
require_once './lib/format.php';

// Chargement du modèle
require_once './model/database.php';
require_once './model/products.php';
require_once './model/customers.php';
require_once './model/employees.php';

// Chargement des datas en  provenance du Model
$outOfStock = getOutOfStockProducts();
$bestSellers = getBestSellersProducts();
$bestCustomers = getBestCustomers();
$bestEmployees = getBestEmployees();
$productLines = getNumberOfProductsByProductLines();

// Chargement du template de la page
include './templates/index.phtml';


