<?php
session_start();
// Les namespace pour eviter d'avoir des riques de conflit avec d'autres librairies
use \Messagerie\Autoloader;
use \Messagerie\MembreManager;
use \Messagerie\MessageManager;
use \Messagerie\Membre;
use \Messagerie\Message;

require 'class/Autoloader.class.php';
require_once('bdd-connect.php');
Autoloader::register();

$membreManager = new MembreManager($db);
$messageManager = new MessageManager($db);

$membres=$membreManager->getList();

// $_SESSION['recepteur']= $_SESSION["pseudo"];

// PHP met en place le $_SESSION['recepteur'] et les revoient en echo à ajax
if (isset($_POST["recepteur"])){
    $_SESSION['recepteur']= $_POST["recepteur"];
}

$emeteur=[
    'pseudo' => $_SESSION['pseudo']
];
$emeteur_instance= new Membre($emeteur);
$id_emeteur= $membreManager->pseudoToId($emeteur_instance);
// echo'Bonjour '.$_SESSION['pseudo'].' vous avez l\'id: '.$id_emeteur.'<BR/>';

$recepteur=[
    'pseudo' => $_SESSION['recepteur']
];
$recepteur_instance= new Membre($recepteur);
$id_recepteur= $membreManager->pseudoToId($recepteur_instance);
    // echo'Vous êtes en conversation avec '.$_SESSION['recepteur'].' qui a l\'id: '.$id_recepteur;

// if (isset($_SESSION['pseudo'])){

    // PHP charge tout les membre et les revoient en echo à ajax
    if(isset($_POST["loadMembre"])){
        for($i=0;$i<count($membres);$i++){
            if ($membres[$i]->getPseudo() !== $_SESSION['pseudo']){
                echo (
                '<a id="change-membre" href="#" class="contact-box">
                    <div class="contact-img"></div>
                    <div class="contact-name-box">
                        <span class="contact-name">'.$membres[$i]->getPseudo().'</span>
                    </div>
                </a>'
                );
            }
        }
    }


    // PHP charge la liste des message et les revoient en echo à ajax
    if (isset($_POST["loadMessage"])){
        $conversation=$messageManager->conversation($id_emeteur, $id_recepteur);
        $_SESSION['countConv']=count($conversation);
        for($i=0;$i<count($conversation);$i++){
            if ($conversation[$i]->getEmeteur()===(int)$id_emeteur){
                echo (
                    '<div class="message-line">
                        <div id="message-box" class="message-emeteur">
                            <div class="message-date emeteur-date">'.$conversation[$i]->getDate().'</div>
                            <div class="message-contenu">'.$conversation[$i]->getContenu().'</div>
                        </div>
                    </div>'
                );
            }
            elseif($conversation[$i]->getEmeteur()===(int)$id_recepteur){
                echo (
                    '<div class="message-line">
                        <div id="message-box" class="message-recepteur">
                            <div class="message-date recepteur-date">'.$conversation[$i]->getDate().'</div>
                            <div class="message-contenu">'.$conversation[$i]->getContenu().'</div>
                        </div>
                    </div>'
                
                );
            }
        }
    }

    // Si la valeur de refresh est déclarée exécute le code
    if (isset($_POST["refresh"])){

        // Assigne à la variable 'messagesNew' les valeurs retournées par la méthode 'refresh' de la classe 'messageManager'
        $messagesNew=$messageManager->refresh($id_emeteur, $id_recepteur);

        // Si l'émetteur du message retourné est égal au récepteur
        if ($messagesNew['emeteur']===$id_recepteur){

            // Assigne la valeur de l'id du message retrouné
            $idMessage=$messagesNew['id_message'];
            // Assigne à la variable 'message' le code HTML d'un message
            $message='<div class="message-line">
                        <div id="message-box" class="message-recepteur">
                            <div class="message-date recepteur-date">'.$messagesNew['date'].'</div>
                            <div class="message-contenu">'.$messagesNew['contenu'].'</div>
                        </div>
                     </div>';

            // Convertie au format JSON le tableau suivant et l'assigne à la variable 'return'
            $return=json_encode(array("id"=>$idMessage, "msg"=>$message));
            
            // Retourne la valeur de la variable 'return'
            echo  $return;
        }
    }

    // PHP envoie un nouveau message et les revoient en echo à ajax
    if (isset($_POST["contenu"]) && !empty($_POST["contenu"])){
            $contenu=[
            'contenu' => $_POST["contenu"]
        ];
        $message_contenu = new Message($contenu);
        $messageEnvoye = $messageManager->send($message_contenu, $id_emeteur, $id_recepteur);
        // $messageManager->send($message_contenu, $id_emeteur, $id_recepteur);
            echo (
                '<div class="message-line">
                    <div id="message-box" class="message-emeteur">
                        <div class="message-date emeteur-date">'.$messageEnvoye['date'].'</div>
                        <div class="message-contenu">'.$messageEnvoye['contenu'].'</div>
                    </div>
                </div>'
            );
    }

    if (isset($_POST["exit"])){
        session_destroy();
    }
// }else{
//     echo 'Connectez vous!';
// }
?>