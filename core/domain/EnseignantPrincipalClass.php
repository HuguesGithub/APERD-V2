<?php
namespace core\domain;

use core\bean\EnseignantPrincipalBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe EnseignantPrincipalClass
 * @author Hugues
 * @since v2.23.01.04
 * @version v2.23.01.04
 */
class EnseignantPrincipalClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $divisionId;
    protected $enseignantId;
    
    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////
    
    public function getDivision()
    { return $this->objDivisionServices->getDivisionById($this->divisionId); }
    
    public function getEnseignant()
    { return $this->objEnseignantServices->getEnseignantById($this->enseignantId); }
    
    public function getLibelle()
    { return $this->getEnseignant()->getName().', '.$this->getDivision()->getField(self::FIELD_LABELDIVISION); }
    
    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        // Définition des champs de l'objet
        $this->arrFields = array(
            self::FIELD_ID => self::FIELD_ID,
            self::FIELD_DIVISIONID => self::LABEL_LABELDIVISION,
            self::FIELD_ENSEIGNANTID => self::LABEL_NOMPRENOM,
        );
    }
    
    /**
     * @return EnseignantPrincipalBean
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function getBean()
    { return new EnseignantPrincipalBean($this); }
    
    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @return string
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function toCsv()
    {
        $arrValues = array();
        array_push($arrValues, $this->getField(self::FIELD_ID));
        array_push($arrValues, $this->getDivision()->getField(self::FIELD_LABELDIVISION));
        array_push($arrValues, $this->getEnseignant()->getName());
        return implode(self::CSV_SEP, $arrValues);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function controlerDonnees(&$notif, &$msg)
    {
        $blnOk = true;
        /////////////////////////////////////////////
        // On doit contrôler divisionId qui doit exister
        // Vu qu'il est renseigné à partir des données importées, soit il est correct, soit il est nul.
        $blnOk = $this->controlerSaisie(self::FIELD_DIVISIONID, $notif, $msg);
        
        // On doit contrôler enseignantId qui doit exister
        // Vu qu'il est renseigné à partir des données importées, soit il est correct, soit il est nul.
        if ($blnOk) {
            $blnOk = $this->controlerSaisie(self::FIELD_ENSEIGNANTID, $notif, $msg);
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
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function controlerImportRow($rowContent, &$notif, &$msg)
    {
        list($id, $labelDivision, $nomEnseignant) = explode(self::CSV_SEP, $rowContent);
        
        //////////////////////////////////////////////////////////////////////////////////
        // rowContent ne contient pas les id, mais le Nom+Prénom pour l'Enseignant
        // et le label de la Division. Il faut donc les rechercher pour définir l'id
        // qui correspond.
        
        // Recherche de la Division
        $attributes = array(self::FIELD_LABELDIVISION=>$labelDivision);
        $objsDivision = $this->objDivisionServices->getDivisionsWithFilters($attributes);
        if (count($objsDivision)==1) {
            $objDivision = array_shift($objsDivision);
            $divisionId = $objDivision->getField(self::FIELD_ID);
        } else {
            $divisionId = '';
        }
        
        // Recherche de l'Enseignant
        $objEnseignant = $this->objEnseignantServices->getEnseignantByNomPrenom($nomEnseignant);
        $enseignantId = $objEnseignant->getField(self::FIELD_ID);
        //////////////////////////////////////////////////////////////////////////////////
        
        // On renseigne l'objet AdulteDivision à partir des données importées.
        $this->setField(self::FIELD_ID, $id);
        $this->setField(self::FIELD_DIVISIONID, $divisionId);
        $this->setField(self::FIELD_ENSEIGNANTID, $enseignantId);
        
        return $this->controlerDonneesAndAct($notif, $msg);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since v2.23.01.04
     * @version v2.23.01.04
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
            $objectInBase = $this->objEnseignantPrincipalServices->getEnseignantPrincipalById($id);
            if ($objectInBase->getField(self::FIELD_ID)=='') {
                $this->insert();
            } else {
                $this->update();
            }
        }
        return true;
    }
    
    /**
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function insert()
    { $this->objEnseignantPrincipalServices->insert($this); }
    
    /**
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function update()
    { $this->objEnseignantPrincipalServices->update($this); }
    
    /**
     * @since v2.23.01.04
     * @version v2.23.01.04
     */
    public function delete()
    { $this->objEnseignantPrincipalServices->delete($this); }
}
