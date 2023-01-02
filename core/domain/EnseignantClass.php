<?php
namespace core\domain;

use core\bean\EnseignantBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EnseignantClass
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class EnseignantClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $genre;
    protected $nomEnseignant;
    protected $prenomEnseignant;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    /**
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getName()
    { return $this->nomEnseignant.' '.$this->prenomEnseignant; }

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        // Définition des champs de l'objet
        $this->arrFields = array(
            self::FIELD_ID => self::FIELD_ID,
            self::FIELD_GENRE => self::LABEL_GENRE,
            self::FIELD_NOMENSEIGNANT => self::LABEL_NOM,
            self::FIELD_PRENOMENSEIGNANT => self::LABEL_PRENOM,
        );
    }

    /**
     * @return EnseignantBean
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getBean()
    { return new EnseignantBean($this); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function controlerDonnees(&$notif, &$msg)
    {
        /////////////////////////////////////////////
        // Le nom doit être renseigné
        return $this->controlerSaisie(self::FIELD_NOMENSEIGNANT, $notif, $msg);
        /////////////////////////////////////////////
        // Fin des contrôles
    }
    
    /**
     * @param string $rowContent
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function controlerImportRow($rowContent, &$notif, &$msg)
    {
        list($id, $genre, $nom, $prenom) = explode(self::CSV_SEP, $rowContent);
        $this->setField(self::FIELD_ID, $id);
        $this->setField(self::FIELD_GENRE, ucfirst(strtolower(trim($genre))));
        $this->setField(self::FIELD_NOMENSEIGNANT, ucfirst(strtolower(trim($nom))));
        $this->setField(self::FIELD_PRENOMENSEIGNANT, ucfirst(strtolower(trim($prenom))));
        return $this->controlerDonneesAndAct($notif, $msg);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function controlerDonneesAndAct(&$notif, &$msg)
    {
        if (!$this->controlerDonnees($notif, $msg)) {
            return false;
        }
        
        // Si les contrôles sont okay, on peut insérer ou mettre à jour
        $id = $this->id;
        if ($id=='') {
            $this->insert();
        } else {
            $objectInBase = $this->objEnseignantServices->getEnseignantById($id);
            if ($objectInBase->getField(self::FIELD_ID)=='') {
                $this->insert();
            } else {
                $this->update();
            }
        }
        return true;
    }
    
    /**
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function insert()
    { $this->objEnseignantServices->insert($this); }
    
    /**
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function update()
    { $this->objEnseignantServices->update($this); }
    
    /**
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function delete()
    { $this->objEnseignantServices->delete($this); }
}
