<?php
namespace core\domain;

use core\bean\MatiereEnseignantBean;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * Classe MatiereEnseignantClass
 * @author Hugues
 * @since v2.23.01.02
 * @version v2.23.01.02
 */
class MatiereEnseignantClass extends LocalDomainClass
{
    //////////////////////////////////////////////////
    // ATTRIBUTES
    //////////////////////////////////////////////////
    protected $id;
    protected $matiereId;
    protected $enseignantId;

    //////////////////////////////////////////////////
    // GETTERS & SETTERS
    //////////////////////////////////////////////////
    
    public function getMatiere()
    { return $this->objMatiereServices->getMatiereById($this->matiereId); }
    
    public function getEnseignant()
    { return $this->objEnseignantServices->getEnseignantById($this->enseignantId); }
    
    public function getLibelle()
    { return $this->getEnseignant()->getName().', '.$this->getMatiere()->getField(self::FIELD_LABELMATIERE); }

    //////////////////////////////////////////////////
    // CONSTRUCT - CLASSVARS - CONVERT - BEAN
    //////////////////////////////////////////////////
    
    /**
     * @param array $attributes
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function __construct($attributes=array())
    {
        parent::__construct($attributes);
        // Définition des champs de l'objet
        $this->arrFields = array(
            self::FIELD_ID => self::FIELD_ID,
            self::FIELD_MATIEREID => self::LABEL_LABELMATIERE,
            self::FIELD_ENSEIGNANTID => self::LABEL_NOMPRENOM,
        );
    }
    /**
     * @return MatiereEnseignantBean
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function getBean()
    { return new MatiereEnseignantBean($this); }

    //////////////////////////////////////////////////
    // METHODS
    //////////////////////////////////////////////////
    
    /**
     * @return string
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function toCsv()
    {
        $arrValues = array();
        array_push($arrValues, $this->getField(self::FIELD_ID));
        array_push($arrValues, $this->getMatiere()->getField(self::FIELD_LABELMATIERE));
        array_push($arrValues, $this->getEnseignant()->getName());
        return implode(self::CSV_SEP, $arrValues);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function controlerDonnees(&$notif, &$msg)
    {
        $blnOk = true;
        /////////////////////////////////////////////
        // On doit contrôler matiereId qui doit exister
        // Vu qu'il est renseigné à partir des données importées, soit il est correct, soit il est nul.
        $blnOk = $this->controlerSaisie(self::FIELD_MATIEREID, $notif, $msg);
        
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
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function controlerImportRow($rowContent, &$notif, &$msg)
    {
        list($id, $labelMatiere, $nomEnseignant) = explode(self::CSV_SEP, $rowContent);
        
        //////////////////////////////////////////////////////////////////////////////////
        // rowContent ne contient pas les id, mais le Nom+Prénom pour l'Enseignant
        // et le label de la MAtière. Il faut donc les rechercher pour définir l'id
        // qui correspond.
        
        // Recherche de la Matière
        $attributes = array(self::FIELD_LABELMATIERE=>$labelMatiere);
        $objsMatiere = $this->objMatiereServices->getMatieresWithFilters($attributes);
        if (count($objsMatiere)==1) {
            $objMatiere = array_shift($objsMatiere);
            $matiereId = $objMatiere->getField(self::FIELD_ID);
        } else {
            $matiereId = '';
        }
        
        // Recherche de l'Enseignant
        $objEnseignant = $this->objEnseignantServices->getEnseignantByNomPrenom($nomEnseignant);
        $enseignantId = $objEnseignant->getField(self::FIELD_ID);
        //////////////////////////////////////////////////////////////////////////////////
        
        // On renseigne l'objet MatiereEnseignant à partir des données importées.
        $this->setField(self::FIELD_ID, $id);
        $this->setField(self::FIELD_MATIEREID, $matiereId);
        $this->setField(self::FIELD_ENSEIGNANTID, $enseignantId);
        
        return $this->controlerDonneesAndAct($notif, $msg);
    }
    
    /**
     * @param string &$notif
     * @param string &$msg
     * @return boolean
     * @since v2.23.01.02
     * @version v2.23.01.02
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
            $objectInBase = $this->objMatiereEnseignantServices->getMatiereEnseignantById($id);
            if ($objectInBase->getField(self::FIELD_ID)=='') {
                $this->insert();
            } else {
                $this->update();
            }
        }
        return true;
    }
    
    /**
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function insert()
    { $this->objMatiereEnseignantServices->insert($this); }
    
    /**
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function update()
    { $this->objMatiereEnseignantServices->update($this); }
    
    /**
     * @since v2.23.01.02
     * @version v2.23.01.02
     */
    public function delete()
    { $this->objMatiereEnseignantServices->delete($this); }
}
