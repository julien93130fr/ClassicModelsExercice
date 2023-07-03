<?php
require_once './model/database.php';

/**
 *  Renvoie toutes les commandes contenant le produit spécifié
 */
function getOrdersByProductCode(string $productCode): array {

    // Connection a la bdd
    $database = connect();

    // Code SQL a executer
    $SQL = 'SELECT `orders`.`orderNumber`, 
                   `orders`.`orderDate`, 
                   `orders`.`customerNumber`, 
                   `customers`.`customerName`, 
                   `orderdetails`.`quantityOrdered`, 
                   `orderdetails`.`priceEach`, 
                   ROUND(`orderdetails`.`quantityOrdered` * `orderdetails`.`priceEach`, 2) AS `total`
            FROM `orders`
            JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
            JOIN `customers` ON `customers`.`customerNumber` = `orders`.`customerNumber`
            WHERE `orderdetails`.`productCode` = :productCode
            ORDER BY `orders`.`orderDate` DESC;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute([
        ':productCode' => $productCode
    ]);

    // Récupération des données de la requete
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);

    //  Renvoie les datas final
    return $datas;
}



/**
 *  Renvoie toutes les commandes d'un client spécifié
 */
function getOrdersByCustomerNumber(string $customerNumber): array {

    // Connection a la bdd
    $database = connect();

    // Code SQL a éxécuter
    $SQL = 'SELECT  `orders`.`orderNumber`,  
                    `orders`.`orderDate`, 
                    `orders`.`requiredDate`, 
                    `orders`.`shippedDate`,
                    `orders`.`status`, 
                    SUM(`orderdetails`.`quantityOrdered`) AS `quantity`,
                    ROUND(SUM(`orderdetails`.`quantityOrdered` * `orderdetails`.`priceEach`), 2) AS `total`
            FROM `orders` 
            JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
            WHERE `orders`.`customerNumber` = :customerNumber
            GROUP BY `orders`.`orderNumber`
            ORDER BY `orders`.`orderDate` DESC;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute([
        ':customerNumber' => $customerNumber
    ]);

    // Récupération des données de la requete
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);

    // Renvoie les données final
    return $datas;
}


/**
 *  Renvoie toutes les commandes d'un client spécifié
 */
function getOrdersByEmployeNumber(string $employeNumber): array {

    // Connection a la bdd
    $database = connect();

    // Code SQL a éxécuter
    $SQL = 'SELECT  `orders`.`orderNumber`,  
                    `orders`.`orderDate`,  
                    `orders`.`requiredDate`,  
                    `orders`.`shippedDate`,  
                    `orders`.`status`,  
                    `customers`.`customerNumber`,  
                    `customers`.`customerName`, 
            SUM(`orderdetails`.`quantityOrdered`) AS `quantity`,
            ROUND(SUM(`orderdetails`.`quantityOrdered` * `orderdetails`.`priceEach`), 2) AS `total`
            FROM `orders` 
            JOIN `customers` ON `customers`.`customerNumber` = `orders`.`customerNumber`
            JOIN `employees` ON `employees`.`employeeNumber` = `customers`.`salesRepEmployeeNumber`
            JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
            WHERE `employees`.`employeeNumber` = :employeNumber
            GROUP BY `orders`.`orderNumber`
            ORDER BY `orders`.`orderDate` DESC;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute([
        ':employeNumber' => $employeNumber
    ]);

    // Récupération des données de la requete
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);

    // RENVOIE LES DATAS FINALES
    return $datas;
}

/**
 * Renvoie le détail d'un commande
 */
function getOrder(string $orderNumber):array{
 
    // Connection a la bdd
    $database = connect();

    // Code SQL a éxécuter
    $SQL = 'SELECT  `orders`.`orderNumber`, 
                    `orders`.`orderDate`, 
                    `orders`.`status`, 
                    `orders`.`comments`, 
                    `customers`.`customerName`, 
                    `customers`.`contactLastName`, 
                    `customers`.`contactFirstName`, 
                    `customers`.`phone`, `customers`.`addressLine1`, 
                    `customers`.`addressLine2`, 
                    `customers`.`postalCode`, 
                    `customers`.`city`, 
                    `customers`.`state`, 
                    `customers`.`country`, 
                    `customers`.`customerNumber`,
                    ROUND(SUM(`orderdetails`.`priceEach` * `orderdetails`.`quantityOrdered`), 2) as `total`
    FROM `orders` 
    JOIN `customers` ON `customers`.`customerNumber` = `orders`.`customerNumber`
    JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
    WHERE `orders`.`orderNumber` = :orderNumber
    GROUP BY `orders`.`orderNumber`;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute([
   ':orderNumber' => $orderNumber
  ]);

    // Récupération des données de la requete
    $datas = $query->fetch(PDO::FETCH_ASSOC);

    // Si il n' y a pas de résultat
    if($datas === false){
        
        // Il faut quand meme envoyer un tableau vide
        $datas = [];
    } 

    // Renvoie les données final
    return $datas;
}

function getOrdersDetails(int $orderNumber): array {

     // Connection a la bdd
    $database = connect();

    // Code SQL a executer
    $SQL = 'SELECT  `orderdetails`.`productCode`,
                    `products`.`productName`,
                    `products`.`productLine`,
                    `products`.`productScale`, 
                    `orderdetails`.`quantityOrdered`, 
                    `orderdetails`.`priceEach`, 
    round(sum(`orderdetails`.`quantityOrdered` * `orderdetails`.`priceEach`),2) AS `Total` 
    FROM `orderdetails` 
    JOIN `products` ON `products`.`productCode` = `orderdetails`.`productCode`
    WHERE `orderdetails`.`orderNumber` = :orderNumber
    GROUP BY `products`.`productCode`;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

     // Exécution de la requete
    $query->execute([
        ':orderNumber' => $orderNumber
    ]);

    // Récupération des données de la requete
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);

     // Renvoie les datas final
    return $datas;
}

function getOrderFull(int $orderNumber): array{
    
    // Connection a la bdd
    $database = connect();

    // Code SQL a éxécuter
    $SQL = 'SELECT  `orders`.`orderNumber`, 
                    `orders`.`orderDate`, 
                    `orders`.`status`, 
                    `orders`.`comments`, 
                    `customers`.`customerName`, 
                    `customers`.`contactLastName`, 
                    `customers`.`contactFirstName`, 
                    `customers`.`phone`, `customers`.`addressLine1`, 
                    `customers`.`addressLine2`, 
                    `customers`.`postalCode`, 
                    `customers`.`city`, 
                    `customers`.`state`, 
                    `customers`.`country`, 
                    `customers`.`customerNumber`,
                    ROUND(SUM(`orderdetails`.`priceEach` * `orderdetails`.`quantityOrdered`), 2) as `total`
    FROM `orders` 
    JOIN `customers` ON `customers`.`customerNumber` = `orders`.`customerNumber`
    JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
    WHERE `orders`.`orderNumber` = :orderNumber
    GROUP BY `orders`.`orderNumber`;';

     // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute([
   ':orderNumber' => $orderNumber
  ]);

    // Récupération des données de la requete
    $datas = $query->fetch(PDO::FETCH_ASSOC);

    // Si il n' y a pas de résultat
    if($datas === false){
        
        // Il faut quand meme envoyer un tableau vide
        $datas = [];
    } 

    // Code SQL a éxécuter
    $SQL = 'SELECT  `orderdetails`.`productCode`,
                    `products`.`productName`,
                    `products`.`productLine`,
                    `products`.`productScale`, 
                    `orderdetails`.`quantityOrdered`, 
                    `orderdetails`.`priceEach`, 
    round(sum(`orderdetails`.`quantityOrdered` * `orderdetails`.`priceEach`),2) AS `Total` 
    FROM `orderdetails` 
    JOIN `products` ON `products`.`productCode` = `orderdetails`.`productCode`
    WHERE `orderdetails`.`orderNumber` = :orderNumber
    GROUP BY `products`.`productCode`;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute([
        ':orderNumber' => $orderNumber
    ]);

    // Récupération des données de la requete
    $datas['lines'] = $query->fetchAll(PDO::FETCH_ASSOC);

    // // Renvoie les données final
    return $datas;
}

