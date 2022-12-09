<?php
namespace core\domain;

use core\services\AdministrationServices;
use core\services\AdulteServices;
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
        $this->objAdministrationServices = new AdministrationServices();
        $this->objAdulteServices         = new AdulteServices();
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










  /**
   * @return string
   * @version 1.21.06.04
   * @since 1.21.06.04
   *
  public function toJson()
  {
    $classVars = $this->getClassVars();
    $str = '';
    foreach ($classVars as $key => $value) {
      if ($str!='') {
        $str .= ', ';
      }
      $str .= '"'.$key.'":'.json_encode($this->getField($key));
    }
    return '{'.$str.'}';
  }

  /**
   * @param array $post
   * @return bool
   * @version 1.21.06.04
   * @since 1.21.06.04
   *
  public function updateWithPost($post)
  {
    $classVars = $this->getClassVars();
    unset($classVars['id']);
    $doUpdate = false;
    foreach ($classVars as $key => $value) {
      if (is_array($post[$key])) {
        $value = stripslashes(implode(';', $post[$key]));
      } else {
        $value = stripslashes($post[$key]);
      }
      if ($this->{$key} != $value) {
        $doUpdate = true;
        $this->{$key} = $value;
      }
    }
    return $doUpdate;
  }
  /**
   * @return int
   * @version 1.21.06.04
   * @since 1.21.06.04
   *
  public static function getWpUserId()
  { return get_current_user_id(); }


  /**
   * @param string &$notif
   * @param string &$msg
   * @version 1.21.06.08
   * @since 1.21.06.01
   *
  public function delete(&$notif, &$msg)
  {
    $this->Services->deleteLocal($this);
    $notif = self::NOTIF_SUCCESS;
    $msg   = self::MSG_SUCCESS_DELETE;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @param array $urlParams
   * @return boolean
   * @version 1.21.06.08
   * @since 1.21.06.01
   *
  public function insert(&$notif, &$msg, $urlParams=array())
  {
    if ($this->controleDonnees($notif, $msg)) {
      $this->Services->insertLocal($this);
      $notif = self::NOTIF_SUCCESS;
      $msg   = self::MSG_SUCCESS_CREATE;
      return true;
    }
    return false;
  }
  /**
   * @param string &$notif
   * @param string &$msg
   * @param array $urlParams
   * @return boolean
   * @version 1.21.06.08
   * @since 1.21.06.01
   *
  public function update(&$notif, &$msg, $urlParams=array())
  {
    if ($this->controleDonnees($notif, $msg)) {
      if ($this->id=='') {
        $notif = self::NOTIF_WARNING;
        $msg   = self::MSG_ERREUR_CONTROL_ID;
      } else {
        $this->Services->updateLocal($this);
        $notif = self::NOTIF_SUCCESS;
        $msg   = self::MSG_SUCCESS_UPDATE;
        return true;
      }
    }
    return false;
  }


  /*
   * @since 1.22.04.27
   * @version 1.22.04.27
   *
  public static function convertRootElement($Obj, $row)
  {
    $vars = $Obj->getClassVars();
    if (!empty($vars)) {
      foreach ($vars as $key => $value) {
        if ($key=='stringClass' || $key=='px_max') {
            // TODO : problème avec px_max, à régler
          continue;
        }
        $Obj->setField($key, $row->{$key});
      }
    }
    return $Obj;
  }

  /*
   * @since 1.22.04.27
   * @version 1.22.04.27
   *
  protected function isAdmin()
  { return current_user_can('manage_options'); }

  /**
   * @return array
   * @version 1.22.04.27
   * @since 1.22.04.27
   *
  public function getClassVars()
  { return get_class_vars($this->stringClass); }
  */
}
