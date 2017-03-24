<?php
namespace Messagerie;
class Message
{
    private $_id;
    private $_date;
    private $_contenu;
    private $_recepteur;
    private $_emeteur;

    public function __construct($donnees)
    {
        $this->hydrate($donnees);
    }

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

    public function getDate()
    {
        return $this->_date;
    }

    public function getContenu()
    {
        return $this->_contenu;
    }

    public function getRecepteur()
    {
        return $this->_recepteur;
    }

    public function getEmeteur()
    {
        return $this->_emeteur;
    }
    
    public function setId($id)
    {
        if (!empty($id)){
            $id = (int) $id;
            $this->_id = $id;
        }
    }
    
    public function setDate($date)
    {
        if (!empty($date) && is_string($date)){
            $this->_date = $date;
        }
    }
    
    public function setContenu($contenu)
    {
        if (!empty($contenu) && is_string($contenu)){
            $this->_contenu = $contenu;
        }
    }
    
    public function setRecepteur($recepteur)
    {
        if (!empty($recepteur)){
            $recepteur = (int) $recepteur;
            $this->_recepteur = $recepteur;
        }
    }
    
    public function setEmeteur($emeteur)
    {
        if (!empty($emeteur)){
            $emeteur = (int) $emeteur;
            $this->_emeteur = $emeteur;
        }
    }
}