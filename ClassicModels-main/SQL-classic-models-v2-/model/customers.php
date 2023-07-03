<?php
require_once './model/database.php';

/**
 * Renvoie la liste des meilleurs clients
 */
function getBestCustomers():array{

    // Connection a la bdd
    $database = connect();

    // Code SQL à éxécuter
    $SQL = 'SELECT `customers`.`customerNumber`, `customers`.`customerName`, ROUND(SUM(`orderdetails`.`quantityOrdered`* `orderdetails`.`priceEach`), 2) AS `CA`
            FROM `customers`
            JOIN `orders` ON `orders`.`customerNumber` = `customers`.`customerNumber`
            JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
            GROUP BY `customers`.`customerNumber`
            ORDER BY `CA` DESC
            LIMIT 3;';

    // Préparation de la requete 
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute();

    // Récupération des  données de la requete 
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);

    // Renvoi les datas finals
    return $datas;
}

/**
 * Renvoie les données du client précisé
 */
function getCustomer(int $customerNumber):array{
    
    // COnnection a la bdd
    $database = connect();

    // Code SQL à éxécuter
    // $SQL = 'SELECT *
    //        FROM `products`
    //        WHERE `productCode` = :productCode';
    $SQL = 'SELECT  `customers`.`customerNumber`, 
                    `customers`.`customerName`, 
                    `customers`.`contactLastName`, 
                    `customers`.`contactFirstName`, 
                    `customers`.`phone`, 
                    `customers`.`addressLine1`, 
                    `customers`.`addressLine2`, 
                    `customers`.`city`, 
                    `customers`.`state`, 
                    `customers`.`postalCode`, 
                    `customers`.`country`, 
                    `customers`.`salesRepEmployeeNumber`, 
                    `customers`.`creditLimit`, 
                    `employees`.`lastName`, 
                    `employees`.`firstName`, 
                    `employees`.`email`, 
                    `offices`.`city` as `officeCity`, 
                    `offices`.`country` as `officeCountry`,
                    ROUND(SUM(`orderdetails`.`quantityOrdered`* `orderdetails`.`priceEach`), 2) AS `CA`
            FROM `customers`
            JOIN `employees` ON `employees`.`employeeNumber` = `customers`.`salesRepEmployeeNumber`
            JOIN `offices` ON `offices`.`officeCode` = `employees`.`officeCode`
            JOIN `orders` ON `orders`.`customerNumber` = `customers`.`customerNumber`
            JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
            WHERE `customers`.`customerNumber` = :customerNumber
            GROUP BY `customers`.`customerNumber`;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute([
         ':customerNumber' => $customerNumber
    ]);

    // Récupération des données de la requete
    $datas = $query->fetch(PDO::FETCH_ASSOC);

    // Si il n' y a pas de résultat
    if($datas === false){
        
        // Il faut quand meme renvoyer un tableau vide
        $datas = [];
    } 

    // Renvoie les datas finales
    return $datas;
}



