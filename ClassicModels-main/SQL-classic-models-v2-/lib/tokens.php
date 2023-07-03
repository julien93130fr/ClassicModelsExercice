<?php
session_start();

/**
 * Renvoie un token aléatoire
 */
function getRandomToken(): string {
    return (bin2hex(random_bytes(8)));
}

/**
 * Mémorise le token spécifié
 */
function saveToken(string $token):void{
    $_SESSION['token'] = $token;
}

/**
 * Vérifie si le token fourni est le bon
 */
function checkToken(string $token): bool {
    if (!isset($_SESSION['token']))
        return false;
    return $_SESSION['token'] == $token;
}