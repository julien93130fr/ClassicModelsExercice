<?php


/**
 * Crée une connexion à la BDD
 */
function connect(){
    // Tentative de connexion a la bdd 
    $dsn = 'mysql:dbname=classicmodels;host=127.0.0.1';
    $user = 'root';
    $password = 'root';
    // Création d'une instance de la classe PDO pour la connexion à la base de données
    $database = new PDO($dsn, $user, $password);

    //// Retourne l'objet PDO pour être utilisé dans d'autres parties du code
    return $database;
}