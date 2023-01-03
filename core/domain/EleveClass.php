<?php
namespace core\domain;

use core\bean\EleveBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EleveClass
 * @author Hugues
 * @since 2.22.12.22
 * @version v2.23.01.03
 */
class EleveClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $nomEleve;
    protected $prenomEleve;
    protected $divisionId;
    protected $delegue;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    /**
     * @return string
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function getName()
    { return $this->nomEleve.' '.$this->prenomEleve; }
    
    public function getDivision()
    { return $this->objDivisionServices->getDivisionById($this->divisionId); }
    
    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        // Définition des champs de l'objet
        $this->arrFields = array(
            self::FIELD_ID => self::FIELD_ID,
            self::FIELD_NOMELEVE => self::LABEL_NOM,
            self::FIELD_PRENOMELEVE => self::LABEL_PRENOM,
            self::FIELD_DIVISIONID => self::LABEL_LABELDIVISION,
            self::FIELD_DELEGUE => self::LABEL_DELEGUE,
        );
    }

    /**
     * @return EleveBean
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function getBean()
    { return new EleveBean($this); }
    
    /**
     * @return string
     * @since v2.23.01.03
     * @version v2.23.01.03
     */
    public function toCsv()
    {
        $arrValues = array();
        array_push($arrValues, $this->getField(self::FIELD_ID));
        array_push($arrValues, $this->getField(self::FIELD_NOMELEVE));
        array_push($arrValues, $this->getField(self::FIELD_PRENOMELEVE));
        array_push($arrValues, $this->getDivision()->getField(self::FIELD_LABELDIVISION));
        array_push($arrValues, $this->getField(self::FIELD_DELEGUE));
        return implode(self::CSV_SEP, $arrValues);
    }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function controlerDonnees(&$notif, &$msg)
    {
        $blnOk = true;
        
        /////////////////////////////////////////////
        // Le nom de l'élève doit être renseigné
        $blnOk = $this->controlerSaisie(self::FIELD_NOMELEVE, $notif, $msg);
        
        /////////////////////////////////////////////
        // Le prénom de l'élève doit être renseigné
        if ($blnOk && !$this->controlerSaisie(self::FIELD_PRENOMELEVE, $notif, $msg)) {
            $blnOk = false;
        }
        
        /////////////////////////////////////////////
        // La division de l'élève doit être renseignée et exister
        if ($blnOk && $this->getDivision()->getField(self::FIELD_ID)=='') {
            $notif = self::NOTIF_DANGER;
            $msg = vsprintf(self::MSG_ERREUR_CONTROL_EXISTENCE, array(self::LABEL_LABELDIVISION));
            $blnOk = false;
        }
        
        /////////////////////////////////////////////
        // Fin des contrôles
        return $blnOk;
    }

    /**
     * @param string $rowContent
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.22
     * @version v2.23.01.03
     */
    public function controlerImportRow($rowContent, &$notif, &$msg)
    {
        list($id, $nomEleve, $prenomEleve, $labelDivision, $delegue) = explode(self::CSV_SEP, $rowContent);
        
        //////////////////////////////////////////////////////////////////////////////////
        // rowContent ne contient pas l'id, mais label de la Division. Il faut donc
        // le rechercher pour définir l'id qui correspond.

        // Recherche de la Division
        $attributes = array(self::FIELD_LABELDIVISION=>$labelDivision);
        $objsDivision = $this->objDivisionServices->getDivisionsWithFilters($attributes);
        if (count($objsDivision)==1) {
            $objDivision = array_shift($objsDivision);
            $divisionId = $objDivision->getField(self::FIELD_ID);
        } else {
            $divisionId = '';
        }
        
        $this->setField(self::FIELD_ID, $id);
        $this->setField(self::FIELD_NOMELEVE, ucfirst(strtolower(trim($nomEleve))));
        $this->setField(self::FIELD_PRENOMELEVE, ucfirst(strtolower(trim($prenomEleve))));
        $this->setField(self::FIELD_DIVISIONID, ucfirst(strtolower(trim($divisionId))));
        $this->setField(self::FIELD_DELEGUE, ucfirst(strtolower(trim($delegue))));
        
        return $this->controlerDonneesAndAct($notif, $msg);
    }

    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.22
     * @version 2.22.12.22
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
            $objectInBase = $this->objEleveServices->getEleveById($id);
            if ($objectInBase->getField(self::FIELD_ID)=='') {
                $this->insert();
            } else {
                $this->update();
            }
        }
        return true;
  }
  
    /**
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function insert()
    { $this->objEleveServices->insert($this); }
    
    /**
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function update()
    { $this->objEleveServices->update($this); }
    
    /**
     * @since 2.22.12.22
     * @version 2.22.12.22
     */
    public function delete()
    { $this->objEleveServices->delete($this); }
}
