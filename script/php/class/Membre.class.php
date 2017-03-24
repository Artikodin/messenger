<?php


// On defini le namespace de la classe pour prévenir tout conflit avec d'autre librairie
namespace Messagerie;
class Membre
{
    // on déclare les propriétés de la classe
    private $_id;
    private $_pseudo;
    private $_password;

    // On déclare la méthode avec pour paramètre un tableau qui va être hydraté pour remplir les propriétés
    public function __construct($donnees)
    {
        $this->hydrate($donnees);
    }

    // La méthode 'hydrate' vérifie pour chaque couple clé valeur si le setter correspondant à la clé existe et lui attribue la valeur liée
    public function hydrate(array $donnees)
    {
        foreach ($donnees as $key => $value)
        {
            $method = 'set'.ucfirst($key);
                
            if (method_exists($this, $method))
            {
                // On appelle le setter.
                $this->$method($value);
            }
        }
    }

    public function getId()
    {
        return $this->_id;
    }

    public function getPseudo()
    {
        return $this->_pseudo;
    }

    public function getPassword()
    {
        return $this->_password;
    }

    public function setId($id)
    {
        if (!empty($id)){
            $id = (int) $id;
            $this->_id = $id;
        }
    }

    public function setPseudo($pseudo)
    {
        if (!empty($pseudo) && is_string($pseudo)){
            $this->_pseudo = $pseudo;
        }
    }

    public function setPassword($password)
    {
        if (!empty($password) && is_string($password)){
            $this->_password = $password;
        }
    }
}