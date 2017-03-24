<?php
namespace Messagerie;
use \PDO;
class MembreManager
{
    private $_db;
  
    public function __construct($db)
    {
        $this->setDb($db);
    }

    // Méthode qui ajoute un membre à la base de donnée
    public function add(Membre $membre, $passwordConfirm)
    {
        // Si un des champs est vide renvoie un message d'erreur
        if (empty($membre->getPseudo()) || empty($membre->getPassword()) || empty($passwordConfirm)){
           $msg = "Attention un des champs est vide!";
        }else{
            // Si les mots de passe sont les mêmes débute l'inscription
            if ($membre->getPassword() === $passwordConfirm){
                
                // Assigne à la variable 'membres' un tableau rempli avec tout les membres
                $membres = $this->getList();
                $validate = false;
                // Pour chaque valeurs du tableau vérifie si le pseudo tapé par l'utilisateur correspond à celui d'un membre éxistant
                for($i=0;$i<count($membres);$i++){
                    if ($membres[$i]->getPseudo() === $membre->getPseudo()){
                        // Si le pseudo existe déjà assigne la valeur true à la variable 'validate'
                        $validate = true;
                    }
                }

                // Le pseudo n'existe pas encore, execute la commande mySQL d'inscription et renvoie un message de réussite
                if ($validate === false){
                    $q = $this->_db->prepare('INSERT INTO membre(pseudo, password) VALUES(:pseudo, :password)');

                    $q->bindValue(':pseudo', $membre->getPseudo());
                    $q->bindValue(':password', $membre->getPassword());

                    $q->execute();
                    $msg = "Inscription réussi félicitation!";
                // Le pseudo existe déjà retourne un message d'erreur
                }else{
                    // Erreur: pseudo déjà existant
                    $msg = "Ce pseudo existe déjà!";
                }
            // Sinon renvoie un message d'erreur comme quoi les mots de passe ne sont pas les mêmes
            }else{
                $msg = "Attention les mots de passe ne sont pas identiques!";
            }
        }
        // Si la variable 'msg' est défini retourne la variable
        if(isset($msg)){
            return $msg;
        }
    }
    
    // Connexion d'un membre à la base de donnée
    public function connexion(Membre $membre)
    {
        if (empty($membre->getPseudo()) || empty($membre->getPassword())){
            // Erreur: Un des deux champs est vide
           $msg = "Attention un des champs est vide!";
        }else{

            $membres = $this->getList();
            $validate = false;
            for($i=0;$i<count($membres);$i++){
                if ($membres[$i]->getPseudo() === $membre->getPseudo()){
                    $validate = true;
                }
            }

            if ($validate === true){
                $q = $this->_db->prepare('SELECT password FROM membre WHERE pseudo = :pseudo');
                $q->bindValue(':pseudo', $membre->getPseudo());
                $q->execute();

                while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
                {   
                    if ($donnees['password'] === $membre->getPassword()){
                        // Connexion réussi
                        $msg = "Connexion réussi";
                        header('Location: /messagerie/messagerie.php'); 
                        $_SESSION['pseudo'] = $membre->getPseudo();
                        $_SESSION['recepteur'] = $membre->getPseudo();
                    }else{
                        // Erreur: Mauvais mot de passe
                        $msg = "Attention le mot de passe n'est pas valide";
                    }
                }
            }else{
                // Erreur: Mauvais pseudo
                $msg = "Attention ce pseudo n'est pas valide!";
            }

        }
        if(isset($msg)){
            return $msg;
        }

    }

    public function get($id)
    {
        $id = (int) $id;

        $q = $this->_db->query('SELECT id, pseudo, password FROM membre WHERE id = '.$id);
        $donnees = $q->fetch(PDO::FETCH_ASSOC);

        return new Membre($donnees);
    }

    public function pseudoToId(Membre $membre)
    {
        $q = $this->_db->prepare('SELECT id FROM membre WHERE pseudo = :pseudo');
        $q->bindValue(':pseudo', $membre->getPseudo());
        $q->execute();
        $donnees = $q->fetch(PDO::FETCH_ASSOC);
        return $donnees["id"];
    }

    public function getList()
    {
        $membres = [];

        $q = $this->_db->query('SELECT id, pseudo, password FROM membre ORDER BY id');

        while ($donnees = $q->fetch(PDO::FETCH_ASSOC))
        {
            $membres[] = new Membre($donnees);
        }
        if(isset($membres)){
    		return $membres;
    	}
    }

    public function update(Membre $membre)
    {
        $q = $this->_db->prepare('UPDATE membre SET pseudo = :pseudo, password = :password WHERE id = :id');

        $q->bindValue(':id', $membre->getId(), PDO::PARAM_INT);
        $q->bindValue(':pseudo', $membre->getPseudo());
        $q->bindValue(':password', $membre->getPassword());

        $q->execute();
    }

    public function delete(Membre $membre)
    {
        $this->_db->exec('DELETE FROM membre WHERE id = '.$membre->getId());
    }

    public function setDb(PDO $db)
    {
        $this->_db = $db;
    }
}