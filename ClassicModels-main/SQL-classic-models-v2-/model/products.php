<?php
require_once './model/database.php';

/**
 * Renvoie la liste des produits presque épuisés
 */
function getOutOfStockProducts():array{

    // Connection a la bdd
    $database = connect();

    // Code SQL a executer
    $SQL = 'SELECT `productCode`, 
                   `productName`, 
                   `productLine`, 
                   `productScale`, 
                   `quantityInStock`
            FROM `products` 
            WHERE `quantityInStock` <= 200;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute();

    // Récupération des données de la requete
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);

    // Renvoie les datas finales
    return $datas;
}

/**
 * Renvoie la liste des meilleures ventes
 */
function getBestSellersProducts():array{

    // Connection a la bdd
    $database = connect();

    // Code SQL a executer
    $SQL = 'SELECT `products`.`productCode`, 
                   `products`.`productName`, 
                   SUM(`orderdetails`.`quantityOrdered`) AS `quantity`
            FROM `products`
            JOIN `orderdetails` ON `orderdetails`.`productCode` = `products`.`productCode`
            GROUP BY `products`.`productCode`
            ORDER BY `quantity` DESC
            LIMIT 20';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute();

    // Récupération des données de la requete
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);

    // Renvoie les datas finales
    return $datas;
}


/**
 * Renvoie le nombre de produit dans chaque catégorie de produits 
 */
function getNumberOfProductsByProductLines(){
 
    // Connection a la bdd
    $database = connect();

    // Code SQL a executer
    $SQL = 'SELECT `productLine`, COUNT(*) AS `quantity`
            FROM `products`
            GROUP BY  `productLine`;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute();

    // Récupération des données de la requete
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);

    // Renvoie les datas finales
    return $datas;
}

/**
 * Renvoie le détail du produit spécifié
 */
function getProduct(string $productCode):array{
 
    // Connection a la bdd
    $database = connect();

    // Code SQL a executer
    $SQL = 'SELECT *
            FROM `products`
            WHERE `productCode` = :productCode';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Préparation de la requete
    $query->execute([
        ':productCode' => $productCode
    ]);

    // Récupération des données de la requete
    $datas = $query->fetch(PDO::FETCH_ASSOC);

    // Si i ln ' y a pas de résultat
    if($datas === false){
        
        // IL FAUT QUAND MÊME RENVOYER UN TABLEAU VIDE
        $datas = [];
    } 

    // Renvoie les datas finales
    return $datas;
}