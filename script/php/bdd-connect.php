<?php
    // Fichier à inclure dans toute les page ou l'accès à la base de données est requit
    try{
        $db = new PDO('mysql:host=localhost; dbname=messagerie; charset=utf8', 'root', '', array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
    }
    catch (Exception $e){
        die('Erreur : ' . $e->getMessage());
    }
?>