<?php
namespace core\domain;

use core\services\AdministrationServices;
use core\services\AdulteServices;
use core\services\AdulteDivisionServices;
use core\services\DivisionServices;
use core\services\EleveServices;
use core\services\EnseignantServices;
use core\services\EnseignantPrincipalServices;
use core\services\MatiereServices;
use core\services\MatiereEnseignantServices;
use core\interfaceimpl\ConstantsInterface;
use core\interfaceimpl\UrlsInterface;
use core\interfaceimpl\LabelsInterface;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe LocalDomain
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.05
 */
class LocalDomainClass implements ConstantsInterface, UrlsInterface, LabelsInterface
{
    protected $arrFields;
    
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function __construct($attributes=array())
    {
        // Initialisation des attributs de l'objet.
        if (!empty($attributes)) {
            foreach ($attributes as $key => $value) {
                $this->setField($key, $value);
            }
        }
        
        // Initialisation des Services
        $this->objAdministrationServices      = new AdministrationServices();
        $this->objAdulteServices              = new AdulteServices();
        $this->objAdulteDivisionServices      = new AdulteDivisionServices();
        $this->objDivisionServices            = new DivisionServices();
        $this->objEleveServices               = new EleveServices();
        $this->objEnseignantServices          = new EnseignantServices();
        $this->objEnseignantPrincipalServices = new EnseignantPrincipalServices();
        $this->objMatiereServices             = new MatiereServices();
        $this->objMatiereEnseignantServices   = new MatiereEnseignantServices();
    }
    
    /**
     * @return array
     * @since 2.22.12.08
     * @version 2.22.12.08
     */
    public function getFields()
    { return $this->arrFields; }
    
    /**
     * @param string $key
     * @param string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getField($key)
    { return (property_exists($this, $key) ? $this->{$key} : null); }
     
    /**
     * @param string $key
     * @param string $value
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function setField($key, $value)
    { if (property_exists($this, $key)) { $this->{$key} = $value; } }

    /**
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getCsvEntete()
    { return implode(self::CSV_SEP, $this->arrFields); }
    
    /**
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function toCsv()
    {
        $arrValues = array();
        foreach (array_keys($this->arrFields) as $key) {
            array_push($arrValues, $this->{$key});
        }
        return implode(self::CSV_SEP, $arrValues);
    }

    /**
     * @param string &$notif
     * @param string &$notifLevel
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function setNotif(&$notif, $notifLevel)
    {
        if ($notifLevel==self::NOTIF_DANGER) {
            $notif = self::NOTIF_DANGER;
        } elseif ($notifLevel==self::NOTIF_WARNING && $notif!=self::NOTIF_DANGER) {
            $notif = self::NOTIF_WARNING;
        } else {
            $notif = $notifLevel;
        }
    }

    /**
     * @param string $field
     * @param string &$notif
     * @param string &$msg
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function controlerSaisie($field, &$notif, &$msg)
    {
        $blnOk = true;
        if (empty($this->{$field})) {
            $this->setNotif($notif, self::NOTIF_DANGER);
            $msg   = vsprintf(self::MSG_ERREUR_CONTROL_EXISTENCE_NORMEE, array($field));
            $blnOk = false;
        }
        return $blnOk;
    }

  /**
   * @param string $rowContent
   * @param string &$notif
   * @param string &$msg
   * @return boolean
   * @version 2.22.12.09
   * @since 2.22.12.09
   */
    public function controlerEntete($rowContent, &$notif, &$msg)
    {
        if (str_replace(self::CST_EOL, '', $rowContent)!=$this->getCsvEntete()) {
            $notif = self::NOTIF_DANGER;
            $msg = sprintf(self::MSG_ERREUR_CONTROL_ENTETE, $this->getCsvEntete());
            return false;
        }
        return true;
    }
}
