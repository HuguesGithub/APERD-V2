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
        if ($blnOk && !$this->controlerSaisie(self::FIELD_LABELPOSTE, $notif, $msg))
		{
            $blnOk = false;
        }
		
		/////////////////////////////////////////////
        // Fin des contrôles
        return $blnOk;
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
