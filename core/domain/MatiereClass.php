<?php
namespace core\domain;

use core\bean\MatiereBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe DivisionClass
 * @author Hugues
 * @since 2.22.12.21
 * @version 2.22.12.21
 */
class MatiereClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $labelMatiere;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        // Définition des champs de l'objet
        $this->arrFields = array(
            self::FIELD_ID => self::FIELD_ID,
            self::FIELD_LABELMATIERE => self::LABEL_LABELMATIERE,
        );
    }

    /**
     * @return MatiereBean
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function getBean()
    { return new MatiereBean($this); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function controlerDonnees(&$notif, &$msg)
    {
        /////////////////////////////////////////////
        // Le libellé doit être renseigné
        return $this->controlerSaisie(self::FIELD_LABELMATIERE, $notif, $msg);

        /////////////////////////////////////////////
        // Fin des contrôles
    }
    
    /**
     * @param string $rowContent
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function controlerImportRow($rowContent, &$notif, &$msg)
    {
        list($id, $labelMatiere) = explode(self::CSV_SEP, $rowContent);
        $this->setField(self::FIELD_ID, $id);
        $this->setField(self::FIELD_LABELMATIERE, trim($labelMatiere));
        
        return $this->controlerDonneesAndAct($notif, $msg);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.21
     * @version 2.22.12.21
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
            $objectInBase = $this->objMatiereServices->getMatiereById($id);
            if ($objectInBase->getField(self::FIELD_ID)=='') {
                $this->insert();
            } else {
                $this->update();
            }
        }
        return true;
    }
    
    /**
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function insert()
    { $this->objMatiereServices->insert($this); }
    
    /**
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function update()
    { $this->objMatiereServices->update($this); }
    
    /**
     * @since 2.22.12.21
     * @version 2.22.12.21
     */
    public function delete()
    { $this->objMatiereServices->delete($this); }
}
