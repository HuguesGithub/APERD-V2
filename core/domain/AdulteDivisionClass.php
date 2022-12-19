<?php
namespace core\domain;

use core\bean\AdulteDivisionBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdulteDivisionClass
 * @author Hugues
 * @since 2.22.12.12
 * @version 2.22.12.12
 */
class AdulteDivisionClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $adulteId;
    protected $divisionId;
    protected $delegue;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////
    
    public function getAdulte()
    { return $this->objAdulteServices->getAdulteById($this->adulteId); }
    
    public function getDivision()
    { return $this->objDivisionServices->getDivisionById($this->divisionId); }
    
    public function getLibelle()
    { return $this->getAdulte()->getName().', '.$this->getDivision()->getField(self::FIELD_LABELDIVISION); }

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        // Définition des champs de l'objet
        $this->arrFields = array(
            self::FIELD_ID => self::FIELD_ID,
            self::FIELD_ADULTEID => self::LABEL_NOMPRENOM,
            self::FIELD_DIVISIONID => self::LABEL_LABELDIVISION,
            self::FIELD_DELEGUE => self::LABEL_DELEGUE,
        );
    }

    /**
     * @return AdulteDivisionBean
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function getBean()
    { return new AdulteDivisionBean($this); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @return string
     * @since 2.22.12.18
     * @version 2.22.12.18
     */
    public function toCsv()
    {
        $arrValues = array();
        array_push($arrValues, $this->getField(self::FIELD_ID));
        array_push($arrValues, $this->getAdulte()->getName());
        array_push($arrValues, $this->getDivision()->getField(self::FIELD_LABELDIVISION));
        array_push($arrValues, $this->getField(self::FIELD_DELEGUE));
        return implode(self::CSV_SEP, $arrValues);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function controlerDonnees(&$notif, &$msg)
    {
        $blnOk = true;

        /////////////////////////////////////////////
        // On doit contrôler adulteId qui doit exister
        // Vu qu'il est renseigné à partir des données importées, soit il est correct, soit il est nul.
        $blnOk = ($this->getField(self::FIELD_ADULTEID)!='');
        
        // On doit contrôler divisionId qui doit exister
        // Vu qu'il est renseigné à partir des données importées, soit il est correct, soit il est nul.
        if ($blnOk) {
            $blnOk = ($this->getField(self::FIELD_DIVISIONID)!='');
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
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function controlerImportRow($rowContent, &$notif, &$msg)
    {
        list($id, $nomAdulte, $labelDivision, $delegue) = explode(self::CSV_SEP, $rowContent);
        
        //////////////////////////////////////////////////////////////////////////////////
        // rowContent ne contient pas les id, mais le Nom+Prénom pour l'Adulte
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
        
        // Recherche de l'Adulte
        $objAdulte = $this->objAdulteServices->getAdulteByNomPrenom($nomAdulte);
        $adulteId = $objAdulte->getField(self::FIELD_ID);
        //////////////////////////////////////////////////////////////////////////////////
        
        // On renseigne l'objet AdulteDivision à partir des données importées.
        $this->setField(self::FIELD_ID, $id);
        $this->setField(self::FIELD_ADULTEID, $adulteId);
        $this->setField(self::FIELD_DIVISIONID, $divisionId);
        $this->setField(self::FIELD_DELEGUE, $delegue);
        
        return $this->controlerDonneesAndAct($notif, $msg);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.12
     * @version 2.22.12.12
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
            $objectInBase = $this->objAdulteDivisionServices->getAdulteDivisionById($id);
            if ($objectInBase->getField(self::FIELD_ID)=='') {
                $this->insert();
            } else {
                $this->update();
            }
        }
        return true;
    }
    
    /**
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function insert()
    { $this->objAdulteDivisionServices->insert($this); }
    
    /**
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function update()
    { $this->objDivisionServices->update($this); }
    
    /**
     * @since 2.22.12.12
     * @version 2.22.12.12
     */
    public function delete()
    { $this->objAdulteDivisionServices->delete($this); }
}
