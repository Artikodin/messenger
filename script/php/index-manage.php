<?php
session_start();
use \Messagerie\Autoloader;
use \Messagerie\MembreManager;
use \Messagerie\Membre;

require 'class/Autoloader.class.php';
require_once('bdd-connect.php');
Autoloader::register();

$membreManager = new MembreManager($db);


// Si les données d'inscription ont bien été rempli
if (isset($_POST["pseudo"]) && isset($_POST["password"]) && isset($_POST["passwordConfirm"])){
    // On rempli un tableau avec les données reçu
    $donnees_ins=[
        'pseudo' => $_POST["pseudo"],
        'password' => $_POST["password"]
    ];
    // Le tableau est passé en paramètre du constructeur de la classe 'Membre'
    $membre_ins = new Membre($donnees_ins);

    // Place en paramètre de la méthode 'add' l'objet 'membre_ins' et la valeur du champs 'passwordConfirm'
    $message_ins = $membreManager->add($membre_ins, $_POST["passwordConfirm"]);

    // Retourne le message renvoyé par la méthode add
    echo $message_ins;
}

if (isset($_POST["pseudo_con"]) && isset($_POST["password_con"])){
    $donnees_con=[
        'pseudo' => $_POST["pseudo_con"],
        'password' => $_POST["password_con"]
    ];
    $membre_con = new Membre($donnees_con);
    $message_con = $membreManager->connexion($membre_con); 
    echo $message_con;
}
?>