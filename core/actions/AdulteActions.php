<?php
namespace core\actions;

use core\domain\AdulteClass;
use core\services\AdulteServices;

if (!defined('ABSPATH')) {
    die('Forbidden');
}
/**
 * AdulteActions
 * @author Hugues
 * @since 1.22.12.08
 * @version 1.22.12.08
 */
class AdulteActions extends LocalActions
{
    //////////////////////////////////////////////////
    // CONSTRUCT
    //////////////////////////////////////////////////
    /**
     * @since 1.22.12.08
     * @version 1.22.12.08
     */
    public static function getCsvExport()
    {
        $arrToExport = array();
        $objAdulteServices = new AdulteServices();

        // On initialise un objet et on récupère l'entête
        $objAdulte = new AdulteClass();
        // On récupère l'entête
        $arrToExport[] = $objAdulte->getCsvEntete();
        
        // On vérifie si on veut tous les éléments ou seulement une sélection
        $ids = $_POST[self::CST_IDS];
        if ($ids==self::CST_ALL) {
            // On récupère toutes les données
            $objsAdulte = $objAdulteServices->getAdultesWithFilters();
            foreach ($objsAdulte as $objAdulte) {
                $arrToExport[] = $objAdulte->toCsv();
            }
        } else {
            // On récupère les données de tous les objets sélectionnés
            $arrIds = explode(',', $ids);
            foreach ($arrIds as $id) {
                if (empty($id)) {
                    continue;
                }
                $objAdulte = $objAdulteServices->getAdulteById($id);
                $arrToExport[] = $objAdulte->toCsv();
            }
        }
        
        // On retourne le message de réussite.
        return LocalActions::exportFile($arrToExport, ucfirst(self::ONGLET_PARENTS));
    }
    
    /**
     * @since 1.22.12.10
     * @version 1.22.12.10
     */
    public static function importFile()
    {
        $importType = $_POST['importType'];
        $dirName    = dirname(__FILE__).'/../../web/rsc/csv-files/';
        $fileName   = $dirName.'import_'.self::ONGLET_PARENTS.'.csv';
        if ($importType==self::ONGLET_PARENTS &&
            is_uploaded_file($_FILES['fileToImport']['tmp_name']) &&
            rename($_FILES['fileToImport']['tmp_name'], $fileName)) {
                $obj = new AdulteActions();
                return $obj->dealWithImport($fileName);
            }
    }
    public function dealWithImport($srcFile)
    {
        $notif = '';
        $msg = '';
        $msgError = '';
        
        $fileContent = file_get_contents($srcFile);
        $arrContent  = explode(self::CST_EOL, $fileContent);
        
        $headerRow   = array_shift($arrContent);
        $objAdulte = new AdulteClass();
        $blnOk       = $objAdulte->controlerEntete($headerRow, $notif, $msgError);
        
        if ($blnOk) {
            $rkRow = 2;
            while (!empty($arrContent) && $blnOk) {
                $rowContent = array_shift($arrContent);
                $blnOk  = $objAdulte->controlerImportRow($rowContent, $notif, $msg);
                if (!$blnOk) {
                    $msgError = 'Ligne '.$rkRow.' : '.$msg;
                }
                $rkRow++;
            }
        }
        
        if ($blnOk) {
            $notif = self::NOTIF_SUCCESS;
            $msg   = self::MSG_SUCCESS_IMPORT;
            $theList = '';
            $jsonAlertBlock = json_encode($this->getDismissableButton($notif, $msg));
            return '{"the-list": '.json_encode($theList).',"alertBlock": '.$jsonAlertBlock.'}';
        } else {
            return '{"alertBlock": '.json_encode($this->getDismissableButton($notif, $msgError)).'}';
        }
    }
    
    public function getDismissableButton($notif, $msg)
    {
        $strContent  = '<div class="alert alert-'.$notif.' alert-dismissible fade show" role="alert">';
        $strContent .= $msg.'<button type="button" class="close" data-dismiss="alert" aria-label="Close">';
        $strContent .= '<span aria-hidden="true">×</span></button></div>';
        return $strContent;
    }
    
}
