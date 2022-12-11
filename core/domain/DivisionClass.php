<?php
namespace core\domain;

use core\bean\DivisionBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe DivisionClass
 * @author Hugues
 * @since 2.22.12.10
 * @version 2.22.12.10
 */
class DivisionClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $labelDivision;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since 2.22.12.10
     * @version 2.22.12.10
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        // Définition des champs de l'objet
        $this->arrFields = array(
            self::FIELD_ID => self::FIELD_ID,
            self::FIELD_LABELDIVISION => self::LABEL_LABELDIVISION,
        );
    }

    /**
     * @return DivisionBean
     * @since 2.22.12.10
     * @version 2.22.12.10
     */
    public function getBean()
    { return new DivisionBean($this); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.10
     * @version 2.22.12.10
     */
    public function controlerDonnees(&$notif, &$msg)
    {
        $blnOk = true;
        
        /////////////////////////////////////////////
        // Le libellé doit être renseigné
        return $this->controlerSaisie(self::FIELD_LABELDIVISION, $notif, $msg);

        /////////////////////////////////////////////
        // Fin des contrôles
    }
    
    /**
     * @param string $rowContent
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.10
     * @version 2.22.12.10
     */
    public function controlerImportRow($rowContent, &$notif, &$msg)
    {
        list($id, $labelDivision) = explode(self::CSV_SEP, $rowContent);
        $this->setField(self::FIELD_ID, $id);
        $this->setField(self::FIELD_LABELDIVISION, trim($labelDivision));
        
        return $this->controlerDonneesAndAct($notif, $msg);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.10
     * @version 2.22.12.10
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
            $objectInBase = $this->objDivisionServices->getDivisionById($id);
            if ($objectInBase->getField(self::FIELD_ID)=='') {
                $this->insert();
            } else {
                $this->update();
            }
        }
        return true;
    }
    
    /**
     * @since 2.22.12.10
     * @version 2.22.12.10
     */
    public function insert()
    { $this->objDivisionServices->insert($this); }
    
    /**
     * @since 2.22.12.10
     * @version 2.22.12.10
     */
    public function update()
    { $this->objDivisionServices->update($this); }
    
    /**
     * @since 2.22.12.10
     * @version 2.22.12.10
     */
    public function delete()
    { $this->objDivisionServices->delete($this); }
}
