<?php
require_once './model/database.php';


function getBestEmployees(): array{
    
    // Connection a la bdd
    $database = connect();

    // Code SQL a executer
    $SQL = 'SELECT `employees`.`employeeNumber`, 
                   `employees`.`lastName`, 
                   `employees`.`firstName`, 
                   `offices`.`city`, 
                   ROUND(SUM(`orderdetails`.`quantityOrdered`* `orderdetails`.`priceEach`), 2) AS `CA`
            FROM `employees`
            JOIN `offices` ON `offices`.`officeCode` = `employees`.`officeCode`
            JOIN `customers` ON `customers`.`salesRepEmployeeNumber` = `employees`.`employeeNumber`
            JOIN `orders` ON `orders`.`customerNumber` = `customers`.`customerNumber`
            JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
            GROUP BY `employees`.`employeeNumber`
            ORDER BY `CA` DESC
            LIMIT 5;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Préparation de la requete
    $query->execute();

    // Récupération des données de la requete
    $datas = $query->fetchAll(PDO::FETCH_ASSOC);

    // Renvoie les datas finales
    return $datas;
}

function getEmployee(int $employeeNumber):array{
 
    // Connection a la bdd
    $database = connect();

    // Code SQL a éxécuter
    $SQL = 'SELECT `employees`.`employeeNumber`,
                    `employees`.`lastName`, 
                    `employees`.`firstName`, 
                    `employees`.`email`, 
                    `employees`.`jobTitle`, 
                    `employees`.`reportsTo`, 
                    `offices`.`city`, 
                    `offices`.`phone`, 
                    `offices`.`officeCode`, 
                    `offices`.`addressLine1`, 
                    `offices`.`addressLine2`, 
                    `offices`.`state`, 
                    `offices`.`country`, 
                    `offices`.`postalCode`, 
                    `offices`.`territory`,
                ROUND(SUM(`orderdetails`.`quantityOrdered`* `orderdetails`.`priceEach`), 2) AS `CA`
                FROM `employees`
                JOIN `offices` ON `offices`.`officeCode`= `employees`.`officeCode`
                JOIN `customers` ON `customers`.`salesRepEmployeeNumber` = `employees`.`employeeNumber`
                JOIN `orders` ON `orders`.`customerNumber` = `customers`.`customerNumber`
                JOIN `orderdetails` ON `orderdetails`.`orderNumber` = `orders`.`orderNumber`
                WHERE `employees`.`employeeNumber` = :employeeNumber;';

    // Préparation de la requete
    $query = $database->prepare($SQL);

    // Exécution de la requete
    $query->execute([
   ':employeeNumber' => $employeeNumber

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