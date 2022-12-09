<?php
namespace core\domain;

use core\bean\AdministrationBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe AdministrationClass
 * @author Hugues
 * @since 2.22.12.05
 * @version 2.22.12.07
 */
class AdministrationClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $genre;
    protected $nomTitulaire;
    protected $labelPoste;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////
    
    /**
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getFullInfo()
    { return $this->getName().', '.$this->labelPoste; }

    /**
     * @return string
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getName()
    { return $this->genre.' '.$this->nomTitulaire; }

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        // Définition des champs de l'objet
        $this->arrFields = array(
            self::FIELD_ID => self::FIELD_ID,
            self::FIELD_GENRE => self::LABEL_GENRE,
            self::FIELD_NOMTITULAIRE => self::LABEL_NOMTITULAIRE,
            self::FIELD_LABELPOSTE => self::LABEL_LABELPOSTE,
        );
    }

    /**
     * @return AdministrationBean
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function getBean()
    { return new AdministrationBean($this); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since 2.22.12.05
     * @version 2.22.12.05
     */
    public function controlerDonnees(&$notif, &$msg)
    {
        $blnOk = true;
        
        /////////////////////////////////////////////
        // Le nom du Titulaire doit être renseigné
        $blnOk = $this->controlerSaisie(self::FIELD_NOMTITULAIRE, $notif, $msg);
        
        /////////////////////////////////////////////
        // Le libellé du Poste doit être renseigné
        if ($blnOk && !$this->controlerSaisie(self::FIELD_LABELPOSTE, $notif, $msg)) {
            $blnOk = false;
        }
        
        /////////////////////////////////////////////
        // Fin des contrôles
        return $blnOk;
    }

  /**
   * @param string $rowContent
   * @param string $sep
   * @param string &$notif
   * @param string &$msg
   * @return boolean
     * @since 2.22.12.08
     * @version 2.22.12.08
   */
	public function controlerImportRow($rowContent, &$notif, &$msg)
	{
		list($id, $genre, $nomTitulaire, $labelPoste) = explode(self::CSV_SEP, $rowContent);
		$this->setField(self::FIELD_ID, $id);
		$this->setField(self::FIELD_GENRE, ucfirst(strtolower(trim($genre))));
		$this->setField(self::FIELD_NOMTITULAIRE, ucfirst(strtolower(trim($nomTitulaire))));
		$this->setField(self::FIELD_LABELPOSTE, trim(str_replace(array("'", self::CST_EOL), array("''", ''), $labelPoste)));

		return $this->controlerDonneesAndAct($notif, $msg);
	}
	
  /**
   * @param string $sep
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
			$objectInBase = $this->objAdministrationServices->getAdministrationById($id);
			if ($objectInBase->getField(self::FIELD_ID)=='') {
				$this->insert();
			} else {
				$this->update();
			}
		}
		return true;
  }
	
  
    /**
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function insert()
    { $this->objAdministrationServices->insert($this); }
    
    /**
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function update()
    { $this->objAdministrationServices->update($this); }
    
    /**
     * @since 2.22.12.07
     * @version 2.22.12.07
     */
    public function delete()
    { $this->objAdministrationServices->delete($this); }
}
