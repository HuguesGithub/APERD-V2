<?php
namespace core\domain;

use core\bean\AdulteBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdulteClass
 * @author Hugues
 * @since 2.22.12.08
 * @version 2.22.12.08
 */
class AdulteClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $nomAdulte;
    protected $prenomAdulte;
    protected $mailAdulte;
    protected $adherent;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////

    /**
     * @return string
     * @since 2.22.12.08
     * @version 2.22.12.08
     */

    public function getName()
    { return $this->nomAdulte.' '.$this->prenomAdulte; }

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        // Définition des champs de l'objet
        $this->arrFields = array(
            self::FIELD_ID => self::FIELD_ID,
            self::FIELD_NOMADULTE => self::LABEL_NOMPRENOM,
            self::FIELD_PRENOMADULTE => self::LABEL_NOMPRENOM,
            self::FIELD_MAILADULTE => self::LABEL_MAIL,
            self::FIELD_ADHERENT => self::LABEL_ADHERENT,
        );
    }

    /**
     * @return AdulteBean
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getBean()
    { return new AdulteBean($this); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
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
        // Le nom doit être renseigné
        $blnOk = $this->controlerSaisie(self::FIELD_NOMADULTE, $notif, $msg);
        
        /////////////////////////////////////////////
        // Le mail doit être renseigné
        if ($blnOk && !$this->controlerSaisie(self::FIELD_MAILADULTE, $notif, $msg)) {
            $blnOk = false;
        }

        /////////////////////////////////////////////
        // Fin des contrôles
        return $blnOk;
    }
    
    /**
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function insert()
    { $this->objAdulteServices->insert($this); }
    
    /**
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function update()
    { $this->objAdulteServices->update($this); }
    
    /**
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function delete()
    { $this->objAdulteServices->delete($this); }
}
