<?php
namespace core\actions;

use core\domain\MatiereClass;
use core\services\MatiereServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * DivisionActions
 * @author Hugues
 * @since 1.22.12.21
 * @version 1.22.12.21
 */
class MatiereActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.21
     * @version 1.22.12.21
     */
    public static function getCsvExport()
    {
        $arrToExport = array();
        $objMatiereServices = new MatiereServices();

        // On initialise un objet et on récupère l'entête
        $objMatiere = new MatiereClass();
        // On récupère l'entête
        $arrToExport[] = $objMatiere->getCsvEntete();
        
        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsMatiere = $objMatiereServices->getMatieresWithFilters();
            foreach ($objsMatiere as $objMatiere) {
                $arrToExport[] = $objMatiere->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objMatiere = $objMatiereServices->getMatiereById($id);
                $arrToExport[] = $objMatiere->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return LocalActions::exportFile($arrToExport, ucfirst(self::ONGLET_MATIERES));
    }
    
    /**
     * @since 1.22.12.21
     * @version 1.22.12.21
     */
    public static function importFile()
    {
        $importType = $_POST['importType'];
        $dirName    = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName   = $dirName.'import_'.self::ONGLET_MATIERES.'.csv';
        if ($importType==self::ONGLET_MATIERES &&
            is_uploaded_file($_FILES['fileToImport']['tmp_name']) &&
            rename($_FILES['fileToImport']['tmp_name'], $fileName)) {
                $obj = new MatiereActions();
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
     * @since v2.22.12.21
     * @version v2.22.12.21
     */
    public function controlerDonneesImport($arrContent, &$notif, &$msg, &$msgError)
    {
        $headerRow   = array_shift($arrContent);
        $objMatiere  = new MatiereClass();
        $blnOk       = $objMatiere->controlerEntete($headerRow, $notif, $msgError);
        
        if ($blnOk) {
            $rkRow = 2;
            while (!empty($arrContent) && $blnOk) {
                $rowContent = array_shift($arrContent);
                $blnOk  = $objMatiere->controlerImportRow($rowContent, $notif, $msg);
                if (!$blnOk) {
                    $msgError = 'Ligne '.$rkRow.' : '.$msg;
                }
                $rkRow++;
            }
        }
        return $blnOk;
    }
}
