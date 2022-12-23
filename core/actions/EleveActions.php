<?php
namespace core\actions;

use core\domain\EleveClass;
use core\services\EleveServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * EleveActions
 * @author Hugues
 * @since 1.22.12.23
 * @version 1.22.12.23
 */
class EleveActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.23
     * @version 1.22.12.23
     */
    public static function getCsvExport()
    {
        $arrToExport = array();
        $objEleveServices = new EleveServices();

        // On initialise un objet et on récupère l'entête
        $objEleve = new EleveClass();
        // On récupère l'entête
        $arrToExport[] = $objEleve->getCsvEntete();
        
        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        $filters = $_POST['filter'];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsEleve = $objEleveServices->getElevesWithFilters();
            foreach ($objsEleve as $objEleve) {
                $arrToExport[] = $objEleve->toCsv();
            }
        } elseif ($ids=='filter') {
            // TODO : gérer les filtres multiples
            list(, $value) = explode('=', $filters);
            if ($value=='oui') {
                $adh = 1;
            } elseif ($value=='non') {
                $adh = 0;
            } else {
                $adh = '%';
            }

            $arrFilters = array(
                    self::FIELD_ADHERENT => $adh,
            );
            // On récupère toutes les données spécifiques au filtre
            $objsEleve = $objEleveServices->getElevesWithFilters($arrFilters);
            foreach ($objsEleve as $objEleve) {
                $arrToExport[] = $objEleve->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objEleve = $objEleveServices->getEleveById($id);
                $arrToExport[] = $objEleve->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return LocalActions::exportFile($arrToExport, ucfirst(self::ONGLET_ELEVES));
    }
    
    /**
     * @since 1.22.12.23
     * @version 1.22.12.23
     */
    public static function importFile()
    {
        $importType = $_POST['importType'];
        $dirName    = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName   = $dirName.'import_'.self::ONGLET_ELEVES.'.csv';
        if ($importType==self::ONGLET_ELEVES &&
            is_uploaded_file($_FILES['fileToImport']['tmp_name']) &&
            rename($_FILES['fileToImport']['tmp_name'], $fileName)) {
                $obj = new EleveActions();
                return $obj->dealWithImport($fileName);
            }
    }
    
    /**
     * Vérifie la validité du fichier importé.
     * @param array $arrContent
     * @param string $notif
     * @param string $msg
     * @param string $msgError
     * @return boolean
     * @since v2.22.12.23
     * @version v2.22.12.23
     */
    public function controlerDonneesImport($arrContent, &$notif, &$msg, &$msgError)
    {
        $headerRow   = array_shift($arrContent);
        $objEleve= new EleveClass();
        $blnOk       = $objEleve->controlerEntete($headerRow, $notif, $msgError);
        
        if ($blnOk) {
            $rkRow = 2;
            while (!empty($arrContent) && $blnOk) {
                $rowContent = array_shift($arrContent);
                $blnOk  = $objEleve->controlerImportRow($rowContent, $notif, $msg);
                if (!$blnOk) {
                    $msgError = 'Ligne '.$rkRow.' : '.$msg;
                }
                $rkRow++;
            }
        }
        return $blnOk;
    }
}
