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
    protected $phoneAdulte;
    protected $roleAdulte;

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
            self::FIELD_PHONEADULTE => self::LABEL_PHONE,
            self::FIELD_ROLEADULTE => self::LABEL_ROLE,
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
    
    public function hasEditorRights()
    { return ($this->roleAdulte>=self::ROLE_EDITEUR); }
    
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
     * @param string $rowContent
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.10
     * @version 2.22.12.10
     */
    public function controlerImportRow($rowContent, &$notif, &$msg)
    {
        list($id, $nom, $prenom, $mail, $adherent, $phone, $role) = explode(self::CSV_SEP, $rowContent);
        $this->setField(self::FIELD_ID, $id);
        $this->setField(self::FIELD_NOMADULTE, ucfirst(strtolower(trim($nom))));
        $this->setField(self::FIELD_PRENOMADULTE, ucfirst(strtolower(trim($prenom))));
        $this->setField(self::FIELD_MAILADULTE, ucfirst(strtolower(trim($mail))));
        $this->setField(self::FIELD_ADHERENT, $adherent);
        $this->setField(self::FIELD_PHONEADULTE, trim($phone));
        $this->setField(self::FIELD_ROLEADULTE, trim($role));
        
        return $this->controlerDonneesAndAct($notif, $msg);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.08
     * @version 2.22.12.08
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
            $objectInBase = $this->objAdulteServices->getAdulteById($id);
            if ($objectInBase->getField(self::FIELD_ID)=='') {
                $this->insert();
            } else {
                $this->update();
            }
        }
        return true;
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
