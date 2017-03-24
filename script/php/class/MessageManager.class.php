<?php
namespace Messagerie;
use \PDO;
class MessageManager
{
    private $_db;
    private $_test;

    public function __construct($db)
    {
        $this->setDb($db);
    }

    public function send(Message $message, $emeteur, $recepteur)
    {   
        $q = $this->_db->prepare('INSERT INTO message(contenu, ext_emeteur, ext_recepteur) VALUES(:contenu, :emeteur, :recepteur)');

        $q->bindValue(':contenu', $message->getContenu());
        $q->bindValue(':emeteur', $emeteur);
        $q->bindValue(':recepteur', $recepteur);

        $q->execute();

        $q2=$this->_db->prepare('SELECT  id_message, DATE_FORMAT(date,\'%H:%i\') date, contenu, ext_emeteur emeteur, ext_recepteur recepteur FROM message WHERE id_message=@@IDENTITY ORDER BY id_message');

        $q2->bindValue(':emeteur', $emeteur);
        $q2->bindValue(':recepteur', $recepteur);

        $q2->execute();

        $donnees = $q2->fetch(PDO::FETCH_ASSOC);
        if ($donnees!==false){
            $return = $donnees;
        }
        if (isset($return)){
            return $return;
        }
    }

    // Méthode qui prend en paramètre l'émetteur et le récepteur d'un message
    public function refresh($emeteur, $recepteur)
    {
        // Assigne à la variable 'q2' le derniere message échangé entre 'emeteur' et 'recepteur' 
        $q2=$this->_db->prepare('SELECT  id_message, DATE_FORMAT(date,\'%H:%i\') date, contenu, ext_emeteur emeteur, ext_recepteur recepteur 
                                 FROM message 
                                 WHERE ext_emeteur IN (:emeteur, :recepteur) AND ext_recepteur IN (:emeteur, :recepteur) 
                                 ORDER BY id_message DESC LIMIT 1');

        // Convertie les valeurs des variables 'emeteur', 'recepteur' en langage MySQL
        $q2->bindValue(':emeteur', $emeteur);
        $q2->bindValue(':recepteur', $recepteur);

        // Exécute la commande MySQL de la variable 'q2'
        $q2->execute();
        
        // Convertie sous forme de tableau le resultat de la commande MySQL de la variable 'q2' et l'assigne à la variable 'donnees'
        $donnees = $q2->fetch(PDO::FETCH_ASSOC);
        // Si la variable 'donnees' est défini retourne la variable 'donnees'
        if ($donnees!==false){
            return $donnees;
        }
    }

    public function conversation($emeteur, $recepteur)
    {
        $messages = [];

        $q = $this->_db->prepare('SELECT id_message id, DATE_FORMAT(date,\'%H:%i\') date, contenu, ext_emeteur emeteur, ext_recepteur recepteur FROM message WHERE ext_emeteur IN (:emeteur, :recepteur) AND ext_recepteur IN (:emeteur, :recepteur) ORDER BY date');
        
        $q->bindValue(':emeteur', $emeteur);
        $q->bindValue(':recepteur', $recepteur);

        $q->execute();

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $messages[] = new Message($donnees);
        }
        if(isset($messages)){
    		return $messages;
    	}
    }

    public function getList()
    {
        $messages = [];

        $q = $this->_db->query('SELECT id_message id, DATE_FORMAT(date,\'%H:%i\') date, contenu, ext_emeteur emeteur, ext_recepteur recepteur FROM message ORDER BY id_message');

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $messages[] = new Message($donnees);
            var_dump($donnees);
        }
        if(isset($messages)){
    		return $messages;
    	}
    }

    public function delete(Message $message)
    {
        $this->_db->exec('DELETE FROM message WHERE id = '.$message->getId());
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}